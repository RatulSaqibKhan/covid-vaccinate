<?php

namespace App\Library;

use App\Utils\Helpers;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Arr;

class PlainAMQPManager
{
    public $config;
    public $channel;
    public $connection;

    public function __construct()
    {
        $this->config = Arr::first(config('queue.connections.rabbitmq.hosts'));

        try {
            $this->makeConnection();
        } catch (Exception $exception) {
            Helpers::writeToLog('RabbitMQ_CONNECT_ERROR', 'critical', $exception);
        }
    }

    private function makeConnection(): void
    {
        $this->connection = new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password'],
            $this->config['vhost']
        );
        $exchange = config('queue.connections.rabbitmq.exchange');
        $serviceQueueMap = config('queue.connections.rabbitmq.queues') ?? [];
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare($exchange, 'topic', false, true, false);

        foreach ($serviceQueueMap as $queue) {
            if (!(isset($queue['name'], $queue['routing_key']))) {
                continue;
            }

            $this->channel->queue_declare($queue['name'], false, true, false, false);
            $this->channel->queue_bind(
                $queue['name'],
                $exchange,
                $queue['routing_key']
            );
        }
    }

    /**
     * @throws Exception
     */
    public function pushRaw($payload, string $queue, bool $retry = true)
    {
        try {
            if ($this->connection === null || $this->connection->isConnected() === false) {
                $this->makeConnection();
            }

            $this->channel->queue_declare($queue, false, true, false, false);

            [$message] = $this->createMessage($payload);

            $this->channel->basic_publish($message, '', $queue, true, false);
        } catch (\Exception $exception) {
            $this->connection->close();

            if ($retry === false) {
                throw $exception;
            }

            Helpers::writeToLog('RabbitMQ_RECONNECT', 'alert', $exception);

            $this->pushRaw($payload, $queue, false);
        }
    }

    /**
     * @throws Exception
     */
    public function pushToExchange($payload, string $routingKey, bool $retry = true): void
    {
        try {
            if ($this->connection === null || $this->connection->isConnected() === false) {
                $this->makeConnection();
            }

            [$message] = $this->createMessage($payload);

            $this->channel->basic_publish($message, config('queue.connections.rabbitmq.exchange'), $routingKey);
        } catch (Exception $exception) {
            $this->connection->close();

            Helpers::writeToLog('RabbitMQ_RECONNECT', 'alert', $exception);

            if ($retry === false) {
                throw $exception;
            }

            Helpers::writeToLog('RabbitMQ_RECONNECT', 'alert', $exception);

            $this->pushToExchange($payload, $routingKey, false);
        }
    }

    private function createMessage($payload)
    {
        $properties = [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ];

        $currentPayload = json_decode($payload, true);

        if ($correlationId = $currentPayload['id'] ?? null) {
            $properties['correlation_id'] = $correlationId;
        }

        $message = new AMQPMessage($payload, $properties);

        return [
            $message,
            $correlationId,
        ];
    }
}

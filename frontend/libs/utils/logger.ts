import winston from "winston";
import { toZonedTime, format as dateFnsFormat } from "date-fns-tz";
const { format } = winston;
const { combine, timestamp } = format;

class Logger {
  public appId: string;
  public queueName?: string;
  public self: winston.Logger;

  private static instance: Logger; // Singleton instance

  private constructor(appId: string) {
    // Make constructor private
    this.appId = appId;
    this.self = winston.createLogger({
      level: process.env.LOG_LEVEL || 'info',
      format: combine(
        timestamp({
          format: () => {
            const dtz = toZonedTime(new Date(), (process.env.APP_TIMEZONE || 'Asia/Dhaka'));
            return dateFnsFormat(dtz, "yyyy-MM-dd'T'HH:mm:ss.SSSxxx", {
              timeZone: (process.env.APP_TIMEZONE || 'Asia/Dhaka'),
            });
          },
        }),
        winston.format.json()
      ),
    });

    this.self.add(new winston.transports.Console());
  }

  // Static method to get the single instance of Logger
  public static getInstance(
    appId: string,
  ): Logger {
    if (!Logger.instance) {
      Logger.instance = new Logger(appId); // Create the instance if it doesn't exist
    }
    return Logger.instance; // Return the existing instance
  }

  // Method to return winston logger instance
  public getLogger(): winston.Logger {
    return this.self;
  }
}

// Export the logger instance
export const logger = Logger.getInstance(
  process.env.APP_NAME || 'Notify Nexus UI',
).getLogger();

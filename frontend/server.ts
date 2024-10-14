import { createServer } from "https";
import { parse } from "url";
import next from "next";
import fs from "fs";
import express from "express";
import { logger } from "./libs/utils/logger";

const port = parseInt(process.env.APP_PORT || "3000", 10);
const dev = process.env.NODE_ENV !== "production";
const app = next({ dev });
const handle = app.getRequestHandler();

const httpsOptions = {
  key: fs.readFileSync("/etc/ssl/certs/server.key"),
  cert: fs.readFileSync("/etc/ssl/certs/server.pem"),
};

app.prepare().then(() => {
  const expressApp = express();

  // Log each HTTP request using winston
  expressApp.use((req, res, next) => {
    logger.info(`${req.method} ${req.url}`);
    next(); // Continue to the next middleware or route handler
  });

  // Handle Next.js requests
  expressApp.all("*", (req, res) => {
    const parsedUrl = parse(req.url!, true);
    handle(req, res, parsedUrl);
  });

  const server = createServer(httpsOptions, expressApp);

  // Error event listener for handling startup errors
  server.on("error", (err: Error) => {
    logger.error(`Error starting server: ${err.message}`);
  });

  // Listen for incoming requests without passing any arguments to the callback
  server.listen(port, () => {
    logger.info(`Server started on https://localhost:${port}`);
  });
});

# Stage 1: Build
ARG NODE_VERSION=20.17.0
ARG NODE_IMAGE_TYPE=slim

FROM node:${NODE_VERSION}-${NODE_IMAGE_TYPE} AS builder

ENV NPM_CONFIG_CACHE="/tmp/npm"

RUN userdel -r node && \
    mkdir -p /app ${NPM_CONFIG_CACHE} && \
    npm config --location=global set cache ${NPM_CONFIG_CACHE}

WORKDIR /app

COPY ./frontend/package*.json /app

RUN echo "-- Install APT Packages --" && \
      apt update && \
      apt upgrade -y && \
      apt install -y curl telnet
RUN echo "-- Install Local NPM Packages --" && \
      npm ci --no-audit
RUN echo "--- Clean Up ---" && \
      apt-get clean -y && \
      apt-get autoremove -y && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
      mkdir -p ${NPM_CONFIG_CACHE} && \
      chmod -R ugo+rw /app/node_modules ${NPM_CONFIG_CACHE}

WORKDIR /app

ARG NODE_EXTRA_CA_CERTS=""

ENV NODE_EXTRA_CA_CERTS="${NODE_EXTRA_CA_CERTS}"

USER root

# Create App User
ARG UID="1000"
ARG GID="1000"

RUN groupadd --gid ${GID} app \
    && useradd --uid ${UID} --create-home --system --comment "App User" --shell /bin/bash --gid app app

RUN chown app:app /app ${NPM_CONFIG_CACHE}

# Copy package.json and package-lock.json (if available)
COPY ./frontend/package*.json ./

# install project dependencies
# Omit --production flag for TypeScript devDependencies
RUN if [ -f yarn.lock ]; then yarn --frozen-lockfile; \
    elif [ -f package-lock.json ]; then npm ci; \
    elif [ -f pnpm-lock.yaml ]; then yarn global add pnpm && pnpm i; \
    # Allow install without lockfile, so example works even without Node.js installed locally
    else echo "Warning: Lockfile not found. It is recommended to commit lockfiles to version control." && npm install; \
    fi

ARG NEXT_PUBLIC_BACKEND_SERVICE_BASE_PROXY_URI
ARG NEXT_PUBLIC_BACKEND_SERVICE_BASE_URI
ARG APP_PORT
ARG APP_NAME
ARG APP_TIMEZONE
ARG LOG_LEVEL

ENV NEXT_PUBLIC_BACKEND_SERVICE_BASE_PROXY_URI=${NEXT_PUBLIC_BACKEND_SERVICE_BASE_PROXY_URI} \
    NEXT_PUBLIC_BACKEND_SERVICE_BASE_URI=${NEXT_PUBLIC_BACKEND_SERVICE_BASE_URI} \
    APP_PORT=${APP_PORT} \
    APP_NAME=${APP_NAME} \
    APP_TIMEZONE=${APP_TIMEZONE} \
    LOG_LEVEL=${LOG_LEVEL}

# Copy the SSL certificate and key from the host
COPY ./docker/certs/* /etc/ssl/certs/

# copy project files 
COPY ./frontend/app ./app
COPY ./frontend/libs ./libs
COPY ./frontend/components ./components
COPY ./frontend/.eslintrc.json .
COPY ./frontend/next.config.mjs .
COPY ./frontend/postcss.config.mjs .
COPY ./frontend/server.ts .
COPY ./frontend/tailwind.config.ts .
COPY ./frontend/tsconfig.json .
COPY ./frontend/tsconfig.server.json .

# Build Next.js based on the preferred package manager
RUN \
  if [ -f yarn.lock ]; then yarn build; \
  elif [ -f package-lock.json ]; then npm run build; \
  elif [ -f pnpm-lock.yaml ]; then pnpm build; \
  else npm run build; \
  fi

# Stage 2: Production
FROM builder AS production

ENV NODE_ENV=production

# Set working directory
WORKDIR /app

# Copy the SSL certificate and key from the host
COPY ./docker/certs/* /etc/ssl/certs/

# Copy only the necessary files from the builder stage
COPY --from=builder /app/package*json ./
COPY --from=builder /app/dist ./dist

# Install only production dependencies
RUN npm ci --omit=dev

# Expose port 3000
EXPOSE 3000

# Start the HTTPS server
CMD ["node", "dist/server.js"]

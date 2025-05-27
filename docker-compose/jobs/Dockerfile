FROM creas

ARG user

ENV USER $user

USER root

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Create the user with the specified username
#RUN useradd -m -s /bin/bash $user

RUN echo "$user        soft  nofile  10000" >> /etc/security/limits.conf
RUN echo "$user        hard  nofile  10000" >> /etc/security/limits.conf

RUN mkdir -p /var/run/supervisor && chown -R $user:$user /var/run/supervisor

COPY ./docker-compose/jobs/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www

USER $user

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

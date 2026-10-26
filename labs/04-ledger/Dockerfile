FROM python:3.11-slim
WORKDIR /app
COPY src/requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY src/ ./
RUN mkdir -p /app/static

# Objective: achieve RCE and read this flag.
RUN echo 'FLAG{bl1nd_sqli_leaked_the_pickle_key}' > /flag.txt && chmod 644 /flag.txt

EXPOSE 80
CMD ["python", "app.py"]

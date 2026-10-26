FROM python:3.11-slim
WORKDIR /app
COPY src/requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY src/ ./
RUN mkdir -p /app/static

# Proof flag reachable only via RCE. (FLAG1 lives in the database; see app.py.)
RUN echo 'FLAG2{pickle_deserialization_rce}' > /proof.txt && chmod 644 /proof.txt

EXPOSE 80
CMD ["python", "app.py"]

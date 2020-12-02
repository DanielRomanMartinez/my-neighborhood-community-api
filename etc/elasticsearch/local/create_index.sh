#!/usr/bin/env bash
echo "Creating mappings and indexes..."
curl -X DELETE "http://localhost:9200/notification_logs"
curl -X PUT "http://localhost:9200/notification_logs" -H "Content-Type: application/json" -d @"notification_logs.json"
echo "\nDone"

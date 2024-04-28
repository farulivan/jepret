#!/bin/bash

echo "Creating S3 bucket..."
awslocal s3api create-bucket --bucket ${BUCKET_NAME} --create-bucket-configuration LocationConstraint=${DEFAULT_REGION}

echo "S3 bucket created successfully."

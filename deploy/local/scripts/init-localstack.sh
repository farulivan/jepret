#!/bin/sh

awslocal s3api create-bucket --bucket ${BUCKET_NAME} --create-bucket-configuration LocationConstraint=${DEFAULT_REGION}
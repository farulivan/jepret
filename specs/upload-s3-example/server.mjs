import express from 'express';
import { join } from 'path';
import { PutObjectCommand, S3Client }  from '@aws-sdk/client-s3';
import { getSignedUrl } from '@aws-sdk/s3-request-presigner';

import { randomUUID } from 'crypto';

const app = express();
const port = process.env.PORT || 8080;

const s3Client = new S3Client({
  region: process.env.AWS_REGION,
  credentials: {
	accessKeyId: process.env.AWS_ACCESS_KEY_ID,
	secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY
  },
  // settings below are required for localstack
  // if we are using real AWS, we can remove these 
  // settings
  endpoint: process.env.AWS_ENDPOINT_URL,
  forcePathStyle: true
});

// serve index.html
app.get('/', function(req, res) {
  res.sendFile(join(import.meta.dirname, '/client.html'));
});

// generate photo url
app.get('/photo-url', async function(req, res) {
	const fileName = randomUUID() + '.jpg';
	const command = new PutObjectCommand({
		Bucket: process.env.AWS_BUCKET_NAME,
		Key: fileName,
		ContentType: 'image/jpeg',
		ACL: 'public-read'
	});
	const url = await getSignedUrl(s3Client, command, { expiresIn: 600 });
	res.type('text/plain').send(url);
});


const server = app.listen(port, () => {
	console.log('Server started at http://localhost:' + port);
});

// handle termination signals
const signals = ['SIGINT', 'SIGTERM'];
for (const signal of signals) {
	process.on(signal, () => {
		console.log(`${signal} signal received: closing HTTP server`);
		server.close(() => {
			console.log('HTTP server closed');
		});
	});
}
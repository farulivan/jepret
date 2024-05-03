# HTTP API

This document contains API specification for Jepret platform.

List of available endpoints:

- [Login](#login)
- [Refresh Access Token](#refresh-access-token)
- [Discover Posts](#discover-posts)
- [Request Photo URL](#request-photo-url)
- [Submit Post](#submit-post)

Beside aware of these endpoints, client is expected to handle [Common Errors](./common_errors.md) as well.

---

## Login

POST: `/session`

This endpoint is used by client to log in the user. Upon successful call, this endpoint returns `access_token` that will be used to authenticate subsequent API calls.

Notice that `access_token` has very short lifetime. In this system the lifetime duration is `20 seconds`. So after `20 seconds` the `access_token` will be expired. 

When the current `access_token` is already expired, client must call [Refresh Access Token](#refresh-access-token) to generate the new `access_token` using `refresh_token` from the response.

Only the following users available in the system:

- email: `alice@mail.com`, password: `123456`
- email: `bob@mail.com`, password: `123456`
- email: `cherry@mail.com`, password: `123456`

> **Note:**
>
> Since `refresh_token` will be used every time the client want to generate new `access_token`, it should be stored in client storage indefinitely.

**Body:**

- `email`, String => The email for login into the system.
- `password`, String => The password for given email.

**Example Request:**

```json
POST /session
Content-Type: application/json

{
    "email": "alice@mail.com",
    "password": "123456"
}
```

**Success Response:**

```json
HTTP/1.1 200 OK
Content-Type: application/json

{
    "ok": true,
    "data": {
        "access_token": "933e89b1-980b-4c98-8d73-18f7ccfac25d",
        "refresh_token": "8eebef3c-03e0-4ead-b78e-27bac3fc43c3",
        "handle": "alice",
        "email": "alice@mail.com"
    }
}
```

**Error Responses:**

- Invalid Credentials (`401`)

    ```json
    HTTP/1.1 401 Unauthorized
    Content-Type: application/json

    {
        "ok": false,
        "err": "ERR_INVALID_CREDS",
        "msg": "incorrect email or password"
    }
    ```

    Client will receive this error when it submitted incorrect combination of email & password.

[Back to Top](#http-api)

---

## Refresh Access Token

PUT: `/session`

This endpoint is used by client to replace expired `access_token` with the new one.

**Header:**

- `Authorization`, String => The value is `Bearer <refresh_token>`.

**Example Request:**

```json
PUT /session
Authorization: Bearer 8eebef3c-03e0-4ead-b78e-27bac3fc43c
```

**Success Response:**

```json
HTTP/1.1 200 OK
Content-Type: application/json

{
    "ok": true,
    "data": {
        "access_token": "933e89b1-980b-4c98-8d73-18f7ccfac25d"
    }
}
```

**Error Responses:**

- Invalid Refresh Token (`401`)

    ```json
    HTTP/1.1 401 Unauthorized
    Content-Type: application/json

    {
        "ok": false,
        "err": "ERR_INVALID_REFRESH_TOKEN",
        "msg": "invalid refresh token"
    }
    ```

    Client will receive this error when it submitted invalid refresh token. There are 2 possibilities of invalid refresh token: either the value is incorrect or the value is deemed expired by the system. In case client receiving this error, it is expected to show login page to the user.

[Back to Top](#http-api)

---

## Discover Posts

GET: `/posts`

This endpoint returns maximum `50` latest posts submitted into the system.

> **Note:**
>
> Everyone can access this endpoint, even if they are not logged in.

**Example Request:**

```json
GET /posts
```

**Success Response:**

```json
HTTP/1.1 200 OK
Content-Type: application/json

{
    "ok": true,
    "data": {
        "posts": [
            {
                "id": 1,
                "photo_url": "https://myphoto.com/444a3a0f-ff24-4db0-8dab-8564e051ce01.jpg",
                "caption": "This is my awesome vacation!",
                "author_id": 1,
                "author_handle": "alice",
                "created_at": 1680356406
            }
        ]
    }
}
```

**Error Responses:**

No specific error response.

[Back to Top](#http-api)

---

## Request Photo URL

POST: `/photo-urls`

This endpoint returns photo URL (called `photo_url`) that will be used by client to upload the photo file (**only photo file not post**) into the system.

After successfully calling this endpoint, client must upload the photo file using `PUT` request to the returned photo URL with addition `x-amz-acl=public-read` in the header. For details on how to upload the photo file please check [this example project](../../upload-s3-example/) (written in NodeJS).

> **Note:**
>
> The returned `photo_url` will also contains security tokens in its query parameters, client must also include these tokens in the URL when uploading the photo file.

**Header:**

- `Authorization`, String => The value is `Bearer <access_token>`.

**Example Request:**

```json
POST /photourls
Authorization: Bearer 933e89b1-980b-4c98-8d73-18f7ccfac25d
```

**Success Response:**

```json
HTTP/1.1 200 OK
Content-Type: application/json

{
    "ok": true,
    "data": {
        "photo_url": "https://myphoto.com/444a3a0f-ff24-4db0-8dab-8564e051ce01.jpg?X-Amz-Algorithm=HMAC&X-Amz-Token=12334deecef232"
    }
}
```

**Error Responses:**

No specific error responses.

[Back to Top](#http-api)

---

## Submit Post

POST: `/posts`

Client used this endpoint to submit new post into the system.

**Header:**

- `Authorization`, String => The value is `Bearer <access_token>`.

**Body:**

- `photo_url`, String => The URL for uploading the photo, it is okay if it doesn't include the security token, because in server it will be removed anyway. But if unsure, client should just put `photo_url` value from [Request Photo URL](#request-photo-url).
- `caption`, String => Photo caption.

**Example Request:**

```json
POST /posts
Content-Type: application/json

{
    "photo_url": "https://myphoto.com/444a3a0f-ff24-4db0-8dab-8564e051ce01.jpg?X-Amz-Algorithm=HMAC&X-Amz-Token=12334deecef232",
    "caption": "This is my awesome vacation!"
}
```

**Success Response:**

```json
HTTP/1.1 200 OK
Content-Type: application/json

{
    "ok": true,
    "data": {
        "id": 1,
        "photo_url": "https://myphoto.com/444a3a0f-ff24-4db0-8dab-8564e051ce01.jpg",
        "caption": "This is my awesome vacation!",
        "author_id": 1,
        "author_handle": "alice",
        "created_at": 1680356406
    }
}
```

**Error Responses:**

No specific error responses.

[Back to Top](#http-api)

---
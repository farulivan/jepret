# MySQL Schema

This document contains specification for MySQL schema that will be used in the system.

## Table `user`

This table contains information of the user registered on the system.

**Fields:**

- `id`, INTEGER(11), AUTO_COMPLETE => id of the record.
- `email`, VARCHAR(64) => email of the user.
- `handle`, VARCHAR(64) => handle (short name) for the user, used when displaying user post.
- `password`, TEXT => password for the user, we are using plain text here for simplification, in production we should never do this! 😅

**Example Records:**

```json
{
    "id": 1,
    "email": "alice@mail.com",
    "handle": "alice",
    "password": "123456"
}
```

**Indexes:**

- `PRIMARY_KEY`, UNIQUE => id
- `email`, UNIQUE => email
- `handle`, UNIQUE => handle

[Back to Top](#mysql-schema)

---

## Table `post`

This table contains information of posts available in the system.

**Fields:**

- `id`, INTEGER(11), AUTO_COMPLETE => id of the record.
- `photo_url`, TEXT => url of the photo.
- `caption`, TEXT => caption for the photo.
- `author_id`, INTEGER(11) => id of the user that posted this photo.
- `created_at`, BIGINT(20) => unix timestamp in seconds when this post is created.

**Example Records:**

```json
{
    "id": 1,
    "photo_url": "https://myphoto.com/444a3a0f-ff24-4db0-8dab-8564e051ce01.jpg",
    "caption": "This is my awesome vacation!",
    "author_id": 1,
    "created_at": 1680356406
}
```

**Indexes:**

- `PRIMARY_KEY`, UNIQUE => id

[Back to Top](#mysql-schema)

---
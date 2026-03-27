Backend setup

1. Install dependencies:

```bash
npm install
```

2. Create a `.env` file (copy from `.env.example`) and set your MySQL credentials.

3. Create the database and table using `db/schema.sql`:

```bash
mysql -u root -p < db/schema.sql
```

4. Start the server:

```bash
npm start
```

The API will be available on the port set in `.env` (default 3000).

Endpoints:
- `GET /api/faqs.php` - list all FAQs (returns JSON)
- `POST /api/chatbot.php` - query message (body: `{message}`) returns `{answer, followups}`
- `POST /api/messages.php` - save messages (body: `{page,message,email,telefono}`)
- `POST /api/submit-case.php` - HTML form endpoint for case requests (redirects to `won.php`)

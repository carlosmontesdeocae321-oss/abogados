const fs = require('fs');
const mysql = require('mysql2/promise');
require('dotenv').config();

async function run() {
  const sql = fs.readFileSync('./db/schema.sql', 'utf8');
  const conn = await mysql.createConnection({
    host: process.env.MYSQL_HOST || '127.0.0.1',
    user: process.env.MYSQL_USER || 'root',
    password: process.env.MYSQL_PASSWORD || '',
    multipleStatements: true
  });
  try {
    console.log('Running schema.sql...');
    await conn.query(sql);
    console.log('Done.');
  } catch (e) {
    console.error('Error running schema:', e);
    process.exit(1);
  } finally {
    await conn.end();
  }
}

run();

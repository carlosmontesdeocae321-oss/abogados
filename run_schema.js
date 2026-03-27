require('dotenv').config();
const fs = require('fs');
const mysql = require('mysql2/promise');
const path = require('path');

(async function(){
  try{
    const sql = fs.readFileSync(path.join(__dirname,'db','schema.sql'),'utf8');
    const conn = await mysql.createConnection({
      host: process.env.MYSQL_HOST || '127.0.0.1',
      user: process.env.MYSQL_USER || 'root',
      password: process.env.MYSQL_PASSWORD || '',
      multipleStatements: true,
      charset: 'utf8mb4'
    });
    console.log('Connected to MySQL, running schema.sql...');
    await conn.query(sql);
    console.log('Schema applied successfully.');
    await conn.end();
  } catch (e) {
    console.error('Error applying schema:', e.message || e);
    process.exit(1);
  }
})();

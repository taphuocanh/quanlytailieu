# quanlytailieu

MySQL database
```sql
CREATE TABLE `document` (
	id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    subj_code int(11),
    typed TEXT,
    bookname TEXT,
    author VARCHAR(255),
    publishdate int(11),
    publisher VARCHAR(255),
    document_not TEXT,
    status int(3),
    storageat VARCHAR(255),
    note TEXT,
    path TEXT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

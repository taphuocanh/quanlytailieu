# quanlytailieu

Use:
*   [n1crack/datatables](https://github.com/n1crack/datatables)
*   [faisalman/simple-excel-php](https://github.com/faisalman/simple-excel-php)


MySQL database
```sql
CREATE TABLE `document` (
    id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    subj_code VARCHAR(255),
    subj_name VARCHAR(255),
    typed TEXT,
    bookname TEXT,
    author VARCHAR(255),
    publishdate int(11),
    publisher VARCHAR(255),
    document_note TEXT,
    status VARCHAR(255),
    storageat VARCHAR(255),
    note TEXT,
    path TEXT
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

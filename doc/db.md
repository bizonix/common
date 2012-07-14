
DB Class
--------

This class facilitates interacting with `PDO` database handles,
by making it even easier to execute prepared statements with bound parameters.
Instead of preparing a statement, binding parameters, and the executing the statement in separate steps,
you can do all of that with just one method call.
Since this class is static, it also helps you access your database from anywhere in your project
without having to pass around `PDO` objects or relying in global variables.

### DB::initialize()

Arguments:

  - mixed $filename_or_pdo

Explanation:

  - The DB class needs to be initialized before any other methods can be called.
    Once it is initialized, it can be referenced from anywhere in your project.
  - It is your responsibility to instantiate a PDO object with the proper hostname, username, password, database name, etc.
    The DB class will not do that for you. But once your PDO object has been set up, you can configure the DB class
    to use it. Just pass the PDO object as an argument to `initialize()`.
  - Alternatively, you can pass a filename as an argument, and the DB class will try to use the file as an SQLite database.
    In this case, both the file and its parent folder must be writable by the PHP script.
  - The DB class will automatically configure PDO to throw exceptions instead of warnings.
    If a database error arises, a `PDOException` will be thrown. The DB class does not use any special exception classes.

### DB::prepare()

Arguments:

  - string $querystring

Explanation:

  - This method is very similar to `PDO->prepare()`.
    It will return the prepared statement as a `PDOStatement` object, and you can do whatever you want with it.
    A `PDOException` may be thrown if a serious error occurs.
  - This method should be used if you wish to use the same statement multiple times.
    If it's a one-off query, you should use `DB::query()` instead, since it is much more convenient.

Usage:

    DB::initialize('/path/to/sqlite/database.db');
    
    $stmt = DB::prepare('INSERT INTO table_name (col1, col2) VALUES (?, ?)');
    $stmt->execute(array('value1', 'value2'));
    $stmt->execute(array('value3', 'value4'));

### DB::query()

Arguments:

  - string $querystring
  - mixed $parameter1, $parameter2, etc.
  
Explanation:
  
  - This method allows you to create a prepared statement and execute it with bound parameters in a single step.
  - The first argument must be a query string, using question marks as placeholders for parameters.
  - You can pass an arbitrary number of additional arguments. These will be bound to each placeholder in the same order.
  - Alternatively, you can pass an array of parameters as the second argument.
    You should use this style if you wish to use named parameters, as the first style does not allow named parameters.
  - This method returns a `PDOStatement` object, from which you can extract query results.
    A `PDOException` may be thrown if a serious error occurs.

Usage:

    DB::initialize('/path/to/sqlite/database.db');
    
    $query1 = DB::query('SELECT * FROM users WHERE id = ?', $id);
    $user = $query1->fetchObject();
    echo $user->name;
    
    $query2 = DB::query('UPDATE users SET name = :name, email = :email WHERE id = :id', array(
        ':id' => 1337,
        ':name' => 'John Doe',
        ':email' => 'john@doe.com',
    ));

### DB::get_pdo()

Arguments: none.

Explanation:

  - This method returns the PDO object that was supplied to `initialize()`.
    This can be useful when you want to access lower-level functionalities of the PDO object
    that the DB class does not expose.

Usage:

    DB::initialize($pdo);
    DB::get_pdo()->setAttribute($attr, $value);

### DB::get_last_insert_id()

Arguments: none.

Explanation:

  - This method returns the ID of the last inserted row.
  - Note that this functionality may not be offered by all database drivers.

### DB::begin_transaction()

Arguments: none.

Explanation:

  - This method begins a transaction.
  - This method returns `TRUE` on success, and `FALSE` on failure.
    In addition, a `PDOException` may be thrown if a serious error occurs.
  - Note that some databases may not support transactions.

### DB::commit()

Arguments: none.

Explanation:

  - This method commits the current transaction.
  - This method returns `TRUE` on success, and `FALSE` on failure.
    In addition, a `PDOException` may be thrown if a serious error occurs.
  - Note that some databases may not support transactions.

### DB::rollback()

Arguments: none.

Explanation:

  - This method rolls back the current transaction.
  - This method returns `TRUE` on success, and `FALSE` on failure.
    In addition, a `PDOException` may be thrown if a serious error occurs.
  - Note that some databases may not support transactions.

### DB::try_begin_transaction()

Arguments: none.

Explanation:

  - This method begins a transaction if no transaction is currently in progress, and does nothing otherwise.
    In other words, this method offers _fake_ nested transactions.
  - This may be useful if you are writing a subroutine that may be reused in different contexts,
    so that the subroutine cannot always assume that a transaction is or isn't currently in progress.
    If no transaction is in progress, the subroutine can make atomic changes to the database;
    otherwise, any changes that the subroutine makes to the database will be atomically committed or discarded
    when the outer routine commits or rolls back the current transaction.
    In either case, you can be sure that the changes made by the subroutine will be atomic,
    even though many open-source databses do not support nested transactions.
    This allows you to have as much atomicity as your database permits, without making your application overly complicated.
    (However, it might often be a better idea to restructure your application slightly.)
  - Some databases support proper nested transactions.
    This method does not use such functionality even if it is available.
  - In PHP 5.3.3 and later, the `PDO` class has an `in_transaction()` method that can be used to determine
    whether or not a transaction is currently in progress.
    However, for compatibility with older versions of PHP, this method does not use the new functionality.
    The DB class uses its own private variables to track whether or not transactions are in progress.
    Therefore, in order for this method to work properly,
    you must only use the DB class's methods to begin, commit, and roll back transactions.
  - Inner transactions must be committed before outer transactions are committed.
  - This method returns a **transaction ID** (int) that you must pass to `try_commit()` later.
    A `PDOException` may be thrown if a serious error occurs.
  - Note that some databases may not support transactions at all.
  - **This functionality is experimental. Please use with caution.**

Usage:

    // In the main routine:
    DB::begin_transaction();
    DB::query($query1);
    DB::query($query2);
    
    // In a subroutine:
    $inner_transaction_id = DB::try_begin_transaction();
    DB::query($query3);
    DB::query($query4);
    DB::try_commit($inner_transaction_id);
    
    // Back in the main routine:
    DB::query($query5);
    DB::query($query6);
    DB::commit();

### DB::try_commit()

Arguments:

  - int $transaction_id

Explanation:

  - This method should be used to commit nested transactions that were begun using `try_begin_transaction()`.
  - If the transaction specified by the ID is the outermost transaction, it will be actually committed.
    If it is an inner transaction, nothing will happen, and the outer transaction will continue to proceed.
  - This method returns `TRUE` if a transaction was actually committed, and `FALSE` otherwise.

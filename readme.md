#Connection - PDO and mysqli wrapper

###Installation
To utilize this class, first import one of the Connection class into your project, and require it.
```
require_once ('ConnectionPDO.php');
```
or
```
require_once ('ConnectionMysqli.php');
```

---

###Example use and difference between PDO:

```
$conn = new ConnectionPDO('localhost', 'root', 'testdb');
$result = $conn->table('test')
               ->select(['foo', 'bar'])
               ->where('foo', ['example1', 'example2'])
               ->get();
```
is equal to
```
$conn = new PDO('"mysql:host=localhost;dbname=testdb;charset=utf8mb4"', 'root', '', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];);

$result = $conn->prepare('SELECT foo, bar 
                        FROM test 
                        WHERE foo IN (:foo1, :foo2)');

$result->execute([
    'foo1' => $foo1, 
    'foo2' => $foo2
]);
    
$result = $result->fetchAll();
```

---

###Methods available:
`table`, `select`, `where`, `like`, `orderBy`, `groupBy`, `get`, `first`.

Input data is automatically escaped (MYSQL Injection protection).

---

###Fetching type:
For fetching type choice simply add a Fetch class as a get() argument.
```
$result = $conn->get(new FetchObject);
```
or
```
$result = $conn->get(new FetchArray);
```
Default fetching type is object.

---

###Structure:

- Classes:
    - **ConnectionPDO** - PDO wrapper
    - **ConnectionMysqli** - mysqli wrapper
    - **ConnectionHelper** - helper class
    - **Fetch, FetchArray, FetchObject** - fetch type (array or object)

- Traits:
    - **ConnectionTrait**

- Interfaces:
    - **ConnectionInterface**
    
---
    
###License
Connection is MIT licensed.

<html>
<title>Результат запроса</title>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    class TableRows extends RecursiveIteratorIterator
    {
        function __construct($it)
        {
            parent::__construct($it, self::LEAVES_ONLY);
        }

        function current()
        {
            return "<td style='width: 150px; border: 1px solid black;'>" . parent::current() . "</td>";
        }

        function beginChildren()
        {
            echo "<tr>";
        }

        function endChildren()
        {
            echo "</tr>" . "\n";
        }
    }

    require_once 'pass.php';
    try {
        $conn = new PDO($dsn);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<table style='border: solid 1px black;'>";

        if ($_POST['options'] == 1) {
            $stmt = $conn->prepare("select * from departments where dep_type = 'Отдел' and bonus1 = 50;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код подразделения</th><th>Бонус 1</th><th>Тип подразделения</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 2) {
            $stmt = $conn->prepare("select * from staff_unit where base_salary >= 30 and base_salary <= 50;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код подразделения</th><th>Код должности</th><th>Базовый оклад</th><th>Бонус 2</th><th>Зарплата</th><th>Размер отпуска</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 3) {
            $name = $_POST['pos_name'];
            $stmt = $conn->prepare("select * from staff_unit 
            where pos_id = 
	        (select pos_id from positions where pos_type = INITCAP('$name')) and base_salary > 50;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код подразделения</th><th>Код должности</th><th>Базовый оклад</th><th>Бонус 2</th><th>Зарплата</th><th>Размер отпуска</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 4) {
            $l = $_POST['lowerBonus1'];
            $h = $_POST['upperBonus1'];
            $stmt = $conn->prepare("select * from departments where dep_type LIKE 'Цех%' and bonus1 between $l and $h;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код подразделения</th><th>Бонус 1</th><th>Название подразделения</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 5) {
            $stmt = $conn->prepare("select * from staff_unit;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код подразделения</th><th>Код должности</th><th>Базовый оклад</th><th>Бонус 2</th><th>Зарплата</th><th>Длительность отпуска</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 6) {
            $stmt = $conn->prepare("
SELECT p.*, s.avg_salary
FROM positions p
INNER JOIN (SELECT pos_id, AVG(salary) AS avg_salary FROM staff_unit GROUP BY pos_id) s
ON p.pos_id = s.pos_id;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код должности</th><th>Название должности</th><th>Средняя зарплата</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    echo "</table>";
    echo "<form method='POST' action='index.html'> <button type = 'submit'>На главную</button></form>";

}

<?php
session_start();
if (!isset($_SESSION['cvList'])) {
    $_SESSION['cvList'] = [];
}

if (isset($_POST['add']) && !empty($_POST['cv'])) {
    $_SESSION['cvList'][] = [
        'ten' => htmlspecialchars($_POST['cv']),
        'xong' => false
    ];
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    unset($_SESSION['cvList'][$id]);
    $_SESSION['cvList'] = array_values($_SESSION['cvList']);
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    if (isset($_SESSION['cvList'][$id])) {
        $_SESSION['cvList'][$id]['xong'] = !$_SESSION['cvList'][$id]['xong'];
    }
}

if (isset($_GET['download'])) {
    $file = "todo_list.txt";
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=$file");
    foreach ($_SESSION['cvList'] as $i => $cv) {
        $status = $cv['xong'] ? "[x] " : "[ ] ";
        echo $status . ($i + 1) . ". " . $cv['ten'] . "\n";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>To-do List</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h2 {
            margin-bottom: 20px;
            color: #1e3c72;
            font-size: 22px;
        }
        input[type=text] {
            padding: 10px;
            width: 65%;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }
        input[type=text]:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 8px rgba(30,60,114,0.5);
        }
        button {
            padding: 10px 15px;
            margin-left: 5px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }
        .add {
            background: #1e90ff;
            color: white;
        }
        .add:hover { background: #0d6efd; }
        .delete {
            background: #ff4757;
            color: white;
        }
        .delete:hover { background: #e84118; }
        .toggle {
            background: #ffa502;
            color: white;
        }
        .toggle:hover { background: #e67e22; }
        .download {
            background: #2ed573;
            color: white;
            margin-top: 10px;
        }
        .download:hover { background: #27ae60; }
        ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        li {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeIn 0.5s ease;
        }
        .xong {
            text-decoration: line-through;
            color: gray;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝To-do List</h2>
        <form method="POST">
            <input type="text" name="cv" placeholder="Nhập công việc...">
            <button type="submit" name="add" class="add">Thêm</button>
        </form>
        <ul>
            <?php foreach ($_SESSION['cvList'] as $i => $cv): ?>
                <li>
                    <span class="<?= $cv['xong'] ? 'xong' : '' ?>">
                        <?= $cv['ten'] ?>
                    </span>
                    <div>
                        <a href="?toggle=<?= $i ?>"><button class="toggle"><?= $cv['xong'] ? '↩' : '✓' ?></button></a>
                        <a href="?delete=<?= $i ?>"><button class="delete">X</button></a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="?download=1"><button class="download">Xuất File</button></a>
    </div>
</body>
</html>

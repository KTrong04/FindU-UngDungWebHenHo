

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include_once __DIR__ . '/../admin/includes/header.php'; $nvql->run_confignChucVu();?>
    <div class="container">
        <?php include_once __DIR__ . '/../admin/includes/sidebar.php'; ?>
        <?php include_once __DIR__ . '/../admin/includes/form_infoNhanVien.php'; ?>
    </div>
    <?php include_once __DIR__ . '/../admin/includes/footer.php'; ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once "includes/head.php"; ?>
    <style>
        canvas {
            width: 700px;
            margin: auto;
            display: block;
        }
    </style>
</head>
<body>
    <main class="p24">
        <?php include_once "includes/sider.php"; ?>

        <div class="main">
            <header class="f-bet r24 p16 bg-blur box-shadow">
                <?php include_once "includes/headerTitle.php"; ?>
                
                <?php include_once "includes/profile.php"; ?>
            </header>

            <div class="p32">
                <canvas id="monthlyChart"></canvas>        
            </div>
        </div>
    </main>

    <script src="scripts/layout.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="scripts/charts.js"></script>
    

</body>
</html>
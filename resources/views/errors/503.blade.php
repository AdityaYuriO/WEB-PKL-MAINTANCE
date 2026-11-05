<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maintenance</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    body {
      background: #ffffff;
      color: #000000;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      text-align: center;
      border: 2px solid #000;
      padding: 40px;
      max-width: 480px;
      width: 90%;
    }

    .icon {
      font-size: 64px;
      margin-bottom: 20px;
      animation: float 2.5s ease-in-out infinite;
    }

    .icon i {
      animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    h1 {
      font-size: 28px;
      margin-bottom: 10px;
      letter-spacing: 1px;
    }

    p {
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 30px;
    }

    .info {
      display: flex;
      justify-content: center;
      gap: 20px;
      font-size: 12px;
    }

    .info div {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    footer {
      margin-top: 30px;
      font-size: 11px;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="icon">
      <i class="fas fa-screwdriver-wrench"></i>
    </div>
    <h1>Sedang Dalam Pemeliharaan</h1>
    <p>
      Web PKL saat ini sedang menjalani proses pemeliharaan dan perbaikan, mohon maaf atas ketidaknyamanannya.
    </p>

    <div class="info">
      <div>
        <i class="fas fa-clock"></i>
        <span>Estimasi selesai segera</span>
      </div>
      <div>
        <i class="fas fa-shield-halved"></i>
        <span>Perbaikan WEB PKL</span>
      </div>
    </div>
  </div>
</body>
</html>

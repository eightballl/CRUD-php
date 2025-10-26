<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Scan Barcode Produk</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      text-align: center;
      padding: 30px;
      background: #f5f7fa;
    }

    h2 {
      color: #333;
      margin-bottom: 20px;
    }

    /* Kotak upload default */
    .upload-area {
      display: inline-block;
      width: 280px;
      height: 200px;
      border: 3px dashed #007bff;
      border-radius: 16px;
      background: #ffffff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      position: relative;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .upload-area:hover {
      background: #eaf3ff;
      border-color: #0056d2;
      transform: scale(1.03);
    }

    .upload-area::before {
      content: "📷";
      font-size: 48px;
      color: #007bff;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -70%);
    }

    .upload-area::after {
      content: "Ambil / Pilih Barcode";
      position: absolute;
      bottom: 25px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 1.1em;
      color: #007bff;
      font-weight: 600;
    }

    #barcodeInput {
      display: none;
    }

    /* Preview container */
    .preview-wrapper {
      position: relative;
      display: inline-block;
      margin-top: 10px;
      width: 100%;
      max-width: 480px;
    }

    /* Gambar preview */
    #preview {
      display: none;
      width: 100%;
      border-radius: 12px;
      border: 3px solid #007bff;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
      transition: all 0.3s ease;
    }

    /* Overlay hover di atas preview */
    .preview-wrapper:hover::after {
      content: "🔁 Ambil Ulang";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 123, 255, 0.6);
      color: #fff;
      font-size: 1.3em;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      pointer-events: none;
      opacity: 1;
      transition: opacity 0.3s ease;
    }

    .preview-wrapper:not(:hover)::after {
      opacity: 0;
    }

    #status {
      margin-top: 15px;
      color: #555;
      font-size: 0.95em;
    }

    #result {
      margin-top: 20px;
      font-size: 1.3em;
      font-weight: bold;
      color: #222;
    }

    /* Spinner loading */
    .spinner {
      display: none;
      margin: 20px auto;
      border: 5px solid #eee;
      border-top: 5px solid #007bff;
      border-radius: 50%;
      width: 45px;
      height: 45px;
      animation: spin 0.9s linear infinite;
    }

    @keyframes spin {
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <h2>📷 Scan Barcode Produk</h2>

  <!-- Area Upload -->
  <label for="barcodeInput" class="upload-area" id="uploadArea"></label>
  <input type="file" id="barcodeInput" accept="image/*" capture="environment">

  <!-- Preview Gambar -->
  <div class="preview-wrapper">
    <img id="preview" alt="Preview Foto Barcode">
  </div>

  <div class="spinner" id="loadingSpinner"></div>

  <div id="status">Klik kotak di atas untuk ambil atau pilih foto barcode.</div>
  <div id="result"></div>

  <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
  <script>
    const barcodeInput = document.getElementById('barcodeInput');
    const preview = document.getElementById('preview');
    const uploadArea = document.getElementById('uploadArea');
    const spinner = document.getElementById('loadingSpinner');
    const statusEl = document.getElementById('status');
    const resultEl = document.getElementById('result');
    let selectedFile = null;

    // Saat user ambil/pilih gambar
    barcodeInput.addEventListener('change', (e) => {
      if (e.target.files && e.target.files[0]) {
        selectedFile = e.target.files[0];
        const url = URL.createObjectURL(selectedFile);
        preview.src = url;
        preview.style.display = 'block';
        uploadArea.style.display = 'none';
        resultEl.innerText = "";
        statusEl.innerText = "📸 Memindai barcode...";
        spinner.style.display = 'block';
        scanBarcode(selectedFile);
      }
    });

    // Klik preview = ambil ulang foto
    preview.addEventListener('click', () => {
      resultEl.innerText = "";
      statusEl.innerText = "📷 Ambil ulang foto barcode.";
      barcodeInput.value = "";
      barcodeInput.click();
    });

    // Fungsi pemindaian barcode
    function scanBarcode(file) {
      const reader = new FileReader();
      reader.onload = function () {
        const dataUrl = reader.result;
        Quagga.decodeSingle({
          src: dataUrl,
          numOfWorkers: 0,
          decoder: { readers: ["ean_reader","code_128_reader","upc_reader","code_39_reader"] }
        }, function (result) {
          spinner.style.display = 'none';
          if (result && result.codeResult) {
            resultEl.innerText = `✅ Barcode: ${result.codeResult.code}`;
            statusEl.innerText = "✅ Barcode berhasil terbaca!";

            fetch("/barcode/store", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({ code: result.codeResult.code })
            });
          } else {
            statusEl.innerText = "❌ Barcode tidak terbaca. Klik foto untuk ambil ulang.";
          }
        });
      };
      reader.readAsDataURL(file);
    }
  </script>
</body>
</html>

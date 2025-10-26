<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan Barcode Produk</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; text-align:center; padding:20px; background:#f8f8f8; }
        #preview { width:100%; max-width:480px; margin:0 auto; border-radius:8px; border:3px solid #444; margin-top:10px;}
        #controls { margin-top:15px; }
        button { background:#007bff; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; margin:5px; font-size:1em; }
        button:disabled { background:#aaa; cursor:not-allowed; }
        #status { margin-top:15px; color:#555; }
        #result { margin-top:20px; font-size:1.3em; font-weight:bold; }
    </style>
</head>
<body>
    <h2>📷 Scan Barcode Produk</h2>

    <input type="file" id="barcodeInput" accept="image/*" capture="environment">
    <img id="preview" src="" alt="Preview" style="display:none;">

    <div id="controls">
        <button id="scanBtn" disabled>Scan Barcode</button>
    </div>

    <div id="status">Pilih gambar atau foto dari kamera untuk memindai.</div>
    <div id="result"></div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script> -->
    <script src="/quagga.min.js"></script>
    <script>
        const barcodeInput = document.getElementById('barcodeInput');
        const preview = document.getElementById('preview');
        const scanBtn = document.getElementById('scanBtn');
        const statusEl = document.getElementById('status');
        const resultEl = document.getElementById('result');
        let selectedFile = null;

        barcodeInput.addEventListener('change', (e) => {
            if(e.target.files && e.target.files[0]){
                selectedFile = e.target.files[0];
                const url = URL.createObjectURL(selectedFile);
                preview.src = url;
                preview.style.display = 'block';
                scanBtn.disabled = false;
                statusEl.innerText = "Gambar siap dipindai. Klik 'Scan Barcode'.";
            }
        });

        scanBtn.addEventListener('click', () => {
            if(!selectedFile) return;

            const reader = new FileReader();
            reader.onload = function() {
                const dataUrl = reader.result;

                Quagga.decodeSingle({
                    src: dataUrl,
                    numOfWorkers: 0,  // 0 = main thread
                    decoder: {
                        readers: ["ean_reader","code_128_reader","upc_reader","code_39_reader"]
                    },
                }, function(result){
                    if(result && result.codeResult) {
                        resultEl.innerText = `📦 Hasil: ${result.codeResult.code}`;
                        statusEl.innerText = "✅ Barcode terbaca!";
                        
                        // Kirim ke backend
                        fetch("/barcode/store", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ code: result.codeResult.code })
                        });
                    } else {
                        statusEl.innerText = "❌ Barcode tidak terbaca, coba lagi.";
                        resultEl.innerText = "";
                    }
                });
            };
            reader.readAsDataURL(selectedFile);
        });
    </script>
</body>
</html>

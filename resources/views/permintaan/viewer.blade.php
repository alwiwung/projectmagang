<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Dokumen - {{ $fileName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Mammoth.js untuk convert DOCX ke HTML -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
    <style>
        body { margin: 0; padding: 0; overflow: hidden; }
        #document-container {
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
            background: white;
            min-height: calc(100vh - 72px);
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
        }
        #document-container img { max-width: 100%; height: auto; }
        #document-container p { margin: 1em 0; }
        #document-container h1, #document-container h2, #document-container h3 {
            margin-top: 1.5em;
            margin-bottom: 0.5em;
        }
        #loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 72px);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ $backUrl }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-all duration-300 font-medium">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-file-word text-xl"></i>
                    <span class="text-lg font-semibold">{{ $fileName }}</span>
                </div>
            </div>
            <div>
                <a href="{{ $downloadUrl }}" 
                   class="inline-flex items-center px-5 py-2 bg-green-500 hover:bg-green-600 rounded-lg transition-all duration-300 font-medium shadow-lg">
                    <i class="fa-solid fa-download mr-2"></i> Download File
                </a>
            </div>
        </div>
    </div>
    
    <!-- Loading -->
    <div id="loading">
        <div class="text-center">
            <i class="fa-solid fa-spinner fa-spin text-5xl text-blue-600 mb-4"></i>
            <p class="text-gray-600">Memuat dokumen...</p>
        </div>
    </div>
    
    <!-- Document Container -->
    <div id="document-container" style="display: none;"></div>
    
    <!-- Error Container -->
    <div id="error-container" style="display: none;" class="min-h-screen flex items-center justify-center p-8">
        <div class="bg-white rounded-2xl shadow-2xl p-12 max-w-2xl w-full text-center">
            <i class="fa-solid fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Tidak Dapat Memuat Dokumen</h2>
            <p class="text-gray-600 mb-6">Format dokumen tidak didukung atau terjadi kesalahan.</p>
            <a href="{{ $downloadUrl }}" 
               class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all">
                <i class="fa-solid fa-download mr-2"></i> Download File
            </a>
        </div>
    </div>

    <script>
    // Fetch dan convert DOCX ke HTML
    fetch("{{ route('permintaan.getFileContent', ['id' => $id, 'type' => $fileType]) }}")
        .then(response => response.arrayBuffer())
        .then(arrayBuffer => mammoth.convertToHtml({arrayBuffer: arrayBuffer}))
        .then(result => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('document-container').style.display = 'block';
            document.getElementById('document-container').innerHTML = result.value;
            
            // Log warnings jika ada
            if (result.messages.length > 0) {
                console.log('Conversion messages:', result.messages);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loading').style.display = 'none';
            document.getElementById('error-container').style.display = 'flex';
        });
</script>
</body>
</html>
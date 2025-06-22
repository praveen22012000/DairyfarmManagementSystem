


       
    <h2>Payment Receipt</h2>

    <img src="{{ $fileUrl }}" alt="Receipt" style="max-width: 90%; border: 1px solid #ccc; padding: 10px;"><br><br>

    <a href="{{ asset('storage/' . $path) }}" download class="btn">
        <button style="padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer;">
            ⬇️ Download Receipt
        </button>
    </a>


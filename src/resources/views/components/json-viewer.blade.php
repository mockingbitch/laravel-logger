<style>
    pre {
        background-color: #f4f4f4;
        color: #333;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        max-height: 300px;
        overflow: auto;
    }

    button {
        margin-top: 10px;
        padding: 10px 15px;
        border: none;
        background-color: #007bff;
        color: #fff;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    button:active {
        background-color: #004080;
    }
</style>
<div class="json-viewer">
    <pre id="json-display"></pre>
    <button id="copy-btn">
        Copy JSON
    </button>
</div>
<script>
    let jsonData = "{{$json}}";
    let decodedString = jsonData.replace(/&quot;/g, '"');
    decodedString = decodedString.replace(/;$/, '');
    jsonData = JSON.parse(decodedString);
    const preElement = document.getElementById('json-display');
    preElement.textContent = JSON.stringify(jsonData, null, 2);
    const copyButton = document.getElementById('copy-btn');
    copyButton.addEventListener('click', () => {
        const tempInput = document.createElement('textarea');
        tempInput.value = JSON.stringify(jsonData, null, 2);
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        copyButton.textContent = 'Copied!';
        setTimeout(() => copyButton.textContent = 'Copy JSON', 2000);
    });
</script>

document.getElementById('checkButton').addEventListener('click', function() {
    const inputType = document.getElementById('inputType').value;
    const inputValue = document.getElementById('inputValue').value;
    
    const options = {
        method: 'GET',
        headers: {
            accept: 'application/json',
            'x-apikey': 'affecd25b067a836e0070ad56c1720cd59308dfd6f05c463f287abee0351b828'
        }
    };

    fetch(`https://www.virustotal.com/api/v3/${inputType}s/${encodeURIComponent(inputValue)}`, options)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('result').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        })
        .catch(error => {
            document.getElementById('result').innerHTML = `<div>Error: ${error.message}</div>`;
        });
});

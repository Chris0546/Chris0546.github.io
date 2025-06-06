// about us knop //
function showContent(content) {
    // Verberg alle content-secties eerst
    document.getElementById('company').style.display = 'none';
    document.getElementById('inspiration').style.display = 'none';

    // Toon de juiste content op basis van de knop die is aangeklikt
    document.getElementById(content).style.display = 'block';
}



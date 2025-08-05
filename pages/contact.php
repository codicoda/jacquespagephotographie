<?php require '../block/header.php'; ?>
<script src="script/contact.js" defer></script>

<p class="paragraph">
Si une ou plusieurs de ces photos vous plaisent au point
de vouloir les acheter, c'est possible : <br>
Je suis auto-entrepreneur (SIREN 983874033)
et je me ferai un plaisir de vous faire parvenir un devis
en fonction des tailles et des supports souhaités.
</p>

<form class="contact" action="script/send" method="post">
    <h2 class="contact-me">📩 Me contacter</h2>
    <label>
        Nom :
        <input type="text" name="nom" required>
    </label>
    <label>
        Email :
        <input type="email" name="email" required>
    </label>
    <label>
        Message :
        <textarea name="message" rows="5" required></textarea>
    </label>
    <button class="send" type="submit">Envoyer</button>
    <div id="messageResult"></div>
</form>

<a href="https://www.facebook.com/people/Jacques-Page-Photographie/61556424717777" target="_blank" class="fb-circle">
    <span class="fb-logo">f</span>
</a>
<?php require '../block/footer.php'; ?>
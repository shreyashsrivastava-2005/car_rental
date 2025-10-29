<?php include("includes/header.php"); ?>

<h2>📞 Contact Us</h2>
<p>If you have any questions about our car rentals, feel free to contact us!</p>

<form action="contact_submit.php" method="POST" style="max-width:500px;">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone"><br><br>

    <label>Subject:</label><br>
    <input type="text" name="subject" required><br><br>

    <label>Message:</label><br>
    <textarea name="message" rows="5" required></textarea><br><br>

    <button type="submit" name="send">Send Message</button>
</form>

<?php include("includes/footer.php"); ?>

<?php
/*
Plugin Name: PC Configurator
Description: Un configurateur de PC pour sélectionner les composants.
Version: 6.9
Author: <a href="https://buildtech.store">Tao Delbar</a>
*/

function pc_configurator_shortcode() {
    ob_start();
    ?>
    <div class="configurator">
        <table>
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Composant</th>
                    <th>Prix</th>
                    <th>Lien</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Processeur</td>
                    <td>
                        <select id="cpu" onchange="updateComponent('cpu')"></select>
                    </td>
                    <td id="cpu-price"></td>
                    <td><a id="cpu-link" href="#" target="_blank">Lien</a></td>
                </tr>
                <tr>
                    <td>Carte Graphique</td>
                    <td>
                        <select id="video-card" onchange="updateComponent('video-card')"></select>
                    </td>
                    <td id="video-card-price"></td>
                    <td><a id="video-card-link" href="#" target="_blank">Lien</a></td>
                </tr>
                <tr>
                    <td>Mémoire (RAM)</td>
                    <td>
                        <select id="memory" onchange="updateComponent('memory')"></select>
                    </td>
                    <td id="memory-price"></td>
                    <td><a id="memory-link" href="#" target="_blank">Lien</a></td>
                </tr>
                <tr>
                    <td>Carte Mère</td>
                    <td>
                        <select id="motherboard" onchange="updateComponent('motherboard')"></select>
                    </td>
                    <td id="motherboard-price"></td>
                    <td><a id="motherboard-link" href="#" target="_blank">Lien</a></td>
                </tr>
                <tr>
                    <td>Alimentation</td>
                    <td>
                        <select id="power-supply" onchange="updateComponent('power-supply')"></select>
                    </td>
                    <td id="power-supply-price"></td>
                    <td><a id="power-supply-link" href="#" target="_blank">Lien</a></td>
                </tr>
                <tr>
                    <td>Stockage</td>
                    <td>
                        <select id="storage" onchange="updateComponent('storage')"></select>
                    </td>
                    <td id="storage-price"></td>
                    <td><a id="storage-link" href="#" target="_blank">Lien</a></td>
                </tr>
                <tr>
                    <td>Boîtier</td>
                    <td>
                        <select id="case" onchange="updateComponent('case')"></select>
                    </td>
                    <td id="case-price"></td>
                    <td><a id="case-link" href="#" target="_blank">Lien</a></td>
                </tr>
            </tbody>
        </table>
        <div class="buttons-container">
            <button onclick="calculatePrice()">Calculer le Prix</button>
            <button id="quoteButton" style="display: none;" onclick="showEmailInput()">Demander un devis pour cette configuration</button>
        </div>
        <div id="totalPrice"></div>
        <div id="quoteMessage" style="margin-top: 20px; display: none;">
            <small>Le prix de ces articles est basé sur ceux d'Amazon, et une marge supplémentaire est ajoutée pour notre profit.</small>
        </div>
        <div id="emailInput" style="margin-top: 20px; display: none;">
            <label for="userEmail">Votre adresse email:</label>
            <input type="email" id="userEmail" required>
            <button onclick="requestQuote()">Envoyer</button>
        </div>
        <div id="confirmationMessage" style="margin-top: 20px; display: none;">
            <small>Merci ! Nous vous répondrons dans les plus brefs délais.</small>
        </div>
    </div>

    <style>
        .configurator {
            font-family: Arial, sans-serif;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .configurator table {
            width: 100%;
            border-collapse: collapse;
        }

        .configurator th, .configurator td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
        }

        .configurator th {
            background-color: #f2f2f2;
        }

        .configurator select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .buttons-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .configurator button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .configurator button:hover {
            background-color: #0056b3;
        }

        #quoteButton {
            background-color: #28a745;
        }

        #quoteButton:hover {
            background-color: #218838;
        }

        #totalPrice {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        #quoteMessage small,
        #confirmationMessage small {
            font-size: 12px;
            color: #6c757d;
        }

        #emailInput {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        #emailInput input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .configurator th, .configurator td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .configurator th::before, .configurator td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
            }

            .configurator th:last-child, .configurator td:last-child {
                border-bottom: 0;
            }

            .configurator td {
                border: none;
                border-bottom: 1px solid #ddd;
            }

            .configurator td select, .configurator td a {
                width: calc(100% - 20px);
                display: inline-block;
                margin-top: 5px;
                margin-bottom: 5px;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch('<?php echo plugins_url('components.json', __FILE__); ?>')
                .then(response => response.json())
                .then(data => {
                    loadComponents(data);
                });

            function loadComponents(data) {
                Object.keys(data).forEach(category => {
                    const select = document.getElementById(category);
                    if (select) {
                        data[category].sort((a, b) => {
                            if (a.brand < b.brand) return -1;
                            if (a.brand > b.brand) return 1;
                            return a.model.localeCompare(b.model);
                        }); // Trier par ordre alphabétique (brand puis model)
                        data[category].forEach(component => {
                            const option = document.createElement('option');
                            option.value = JSON.stringify(component);
                            option.text = `${component.brand} ${component.model}`;
                            select.add(option);
                        });
                        updateComponent(category); // Set initial value for each category
                    }
                });
            }
        });

        function updateComponent(category) {
            const select = document.getElementById(category);
            const component = JSON.parse(select.value);
            document.getElementById(`${category}-price`).innerText = component.price_eur.toFixed(2) + ' €';
            document.getElementById(`${category}-link`).href = component.link;
        }

        function calculatePrice() {
            let totalPrice = 0;
            document.querySelectorAll('select').forEach(select => {
                const component = JSON.parse(select.value);
                totalPrice += component.price_eur;
            });
            document.getElementById('totalPrice').innerText = 'Prix Total: ' + totalPrice.toFixed(2) + ' €';
            document.getElementById('quoteButton').style.display = 'block';
            document.getElementById('quoteMessage').style.display = 'block';
        }

        function showEmailInput() {
            document.getElementById('emailInput').style.display = 'block';
        }

        function requestQuote() {
            const config = {};
            document.querySelectorAll('select').forEach(select => {
                const component = JSON.parse(select.value);
                config[select.id] = component;
            });
            const userEmail = document.getElementById('userEmail').value;
            const subject = 'Demande de devis pour configuration PC';
            const body = `
                Ce mail vient de ${userEmail}<br><br>
                Voici la configuration sélectionnée:<br><br>
                <ul>
                    <li>Processeur: ${config.cpu.brand} ${config.cpu.model} - ${config.cpu.price_eur} € - <a href="${config.cpu.link}">Lien</a></li>
                    <li>Carte Graphique: ${config['video-card'].brand} ${config['video-card'].model} - ${config['video-card'].price_eur} € - <a href="${config['video-card'].link}">Lien</a></li>
                    <li>Mémoire: ${config.memory.brand} ${config.memory.model} - ${config.memory.price_eur} € - <a href="${config.memory.link}">Lien</a></li>
                    <li>Carte Mère: ${config.motherboard.brand} ${config.motherboard.model} - ${config.motherboard.price_eur} € - <a href="${config.motherboard.link}">Lien</a></li>
                    <li>Alimentation: ${config['power-supply'].brand} ${config['power-supply'].model} - ${config['power-supply'].price_eur} € - <a href="${config['power-supply'].link}">Lien</a></li>
                    <li>Stockage: ${config.storage.brand} ${config.storage.model} - ${config.storage.price_eur} € - <a href="${config.storage.link}">Lien</a></li>
                    <li>Boîtier: ${config.case.brand} ${config.case.model} - ${config.case.price_eur} € - <a href="${config.case.link}">Lien</a></li>
                </ul>
                <br>Le prix de ces articles est basé sur ceux d'Amazon.com.be, et une marge supplémentaire est ajoutée pour notre profit.
            `;
            const recipient = 'service@buildtech.store';
            
            fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    action: "send_quote_email",
                    email: recipient,
                    subject: subject,
                    body: body,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('confirmationMessage').style.display = 'block';
                    document.getElementById('emailInput').style.display = 'none';
                } else {
                    alert('Erreur lors de l\'envoi de l\'email.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'envoi de l\'email.');
            });
        }
    </script>
    <?php
    return ob_get_clean();
}

function send_quote_email() {
    $email = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $body = wp_kses_post($_POST['body']); // wp_kses_post permet l'usage de certaines balises HTML dans le contenu

    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: no-reply@yourdomain.com');

    $sent = wp_mail($email, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

add_shortcode('pc_configurator', 'pc_configurator_shortcode');
add_action('wp_ajax_send_quote_email', 'send_quote_email');
add_action('wp_ajax_nopriv_send_quote_email', 'send_quote_email');

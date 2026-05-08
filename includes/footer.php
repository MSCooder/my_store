<style>
        footer {
            background-color: #050505;
            color: #444;
            text-align: center;
            padding: 40px 20px;
            margin-top: 50px;
            border-top: 1px solid #1a1a1a;
            font-family: 'Courier New', Courier, monospace;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-logo {
            color: var(--matrix-green, #00ff41);
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .footer-links {
            margin: 15px 0;
        }

        .footer-links a {
            color: #888;
            text-decoration: none;
            margin: 0 15px;
            font-size: 13px;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: #00ff41;
        }

        .copyright {
            font-size: 12px;
            margin-top: 20px;
            color: #333;
        }

        .neon-line {
            height: 1px;
            width: 50px;
            background-color: #00ff41;
            margin: 20px auto;
            box-shadow: 0 0 10px #00ff41;
        }
    </style>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">My STORE</div>
            <p style="font-size: 14px;">Premium Cyber Hardware & Software Solutions</p>
            
            <div class="neon-line"></div>

            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="cart.php">Cart</a>
                <a href="login.php">Account</a>
                <a href="#">Privacy Policy</a>
            </div>

            <p class="copyright">
                &copy; <?php echo date("Y"); ?> My Store. All Rights Reserved. <br>
                <span style="color: #1a1a1a;">Built with Core PHP & MySQL</span>
            </p>
        </div>
    </footer>

</body>
</html>
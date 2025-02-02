<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Cadastro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imask/6.4.3/imask.min.js"></script>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <!-- Abas de navegação -->
        <div class="flex mb-4">
            <button id="loginTab" class="flex-1 py-2 px-4 bg-blue-500 text-white rounded-tl-lg">Login</button>
            <button id="registerTab" class="flex-1 py-2 px-4 bg-gray-200 rounded-tr-lg">Cadastro</button>
        </div>

        <!-- Formulário de Login -->
        <form id="loginForm" class="space-y-4" method="post" action="interfaceLogin.php">
            <div>
                <label for="loginEmail" class="block mb-1">Email</label>
                <input type="email" id="loginEmail" name="email" class="w-full px-3 py-2 border rounded-md" required>
            </div>
            <div>
                <label for="loginPassword" class="block mb-1">Senha</label>
                <input type="password" id="loginPassword" name="senha" class="w-full px-3 py-2 border rounded-md" required>
            </div>
            <?php 
                if (isset($_GET['mensagem'])) {
            ?>
                <div>
                    <span style="color: red;">
                        <?= $_GET['mensagem'];?>
                    </span>
                </div>

            <?php
                }
            ?>
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Entrar</button>
        </form>

        <!-- Formulário de Cadastro (inicialmente oculto) -->
        <form id="registerForm" class="space-y-4 hidden" method="post" action="interface.php">
                <input type="hidden" name="path" value="index.php">                
                <input type="hidden" id="formMode" name="formMode" value="add">
            <div>
                <label for="registerName" class="block mb-1">Nome</label>
                <input type="text" id="registerName" name="nome" class="w-full px-3 py-2 border rounded-md" required>
            </div>
            <div>
                <label for="registerEmail" class="block mb-1">Email</label>
                <input type="email" id="registerEmail" name="email" class="w-full px-3 py-2 border rounded-md" required>
            </div>
            <div>
                <label for="registerPhone" class="block mb-1">Telefone</label>
                <input type="tel" id="registerPhone" name="telefone" class="w-full px-3 py-2 border rounded-md" required>
            </div>
            <div>
                <label for="registerCPF" class="block mb-1">CPF</label>
                <input type="text" id="registerCPF" name="cpf" class="w-full px-3 py-2 border rounded-md" required>
                <p id="cpfError" class="text-red-500 text-sm mt-1 hidden">CPF inválido</p>
            </div>
            <div>
                <label for="registerPassword" class="block mb-1">Senha</label>
                <input type="password" id="registerPassword" name="senha" class="w-full px-3 py-2 border rounded-md" required>
            </div>
            <div>
                <label for="confirmPassword" class="block mb-1">Confirmar Senha</label>
                <input type="password" id="confirmPassword" name="confirmSenha" class="w-full px-3 py-2 border rounded-md" required>
            </div>
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Cadastrar</button>
        </form>
    </div>

    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('bg-blue-500', 'text-white');
            loginTab.classList.remove('bg-gray-200');
            registerTab.classList.add('bg-gray-200');
            registerTab.classList.remove('bg-blue-500', 'text-white');
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('bg-blue-500', 'text-white');
            registerTab.classList.remove('bg-gray-200');
            loginTab.classList.add('bg-gray-200');
            loginTab.classList.remove('bg-blue-500', 'text-white');
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        });

        // Máscara para o campo de telefone
        const phoneInput = document.getElementById('registerPhone');
        const phoneMask = IMask(phoneInput, {
            mask: '(00) 00000-0000'
        });

        // Máscara para o campo de CPF
        const cpfInput = document.getElementById('registerCPF');
        const cpfMask = IMask(cpfInput, {
            mask: '000.000.000-00'
        });

        // Função para validar CPF
        function validateCPF(cpf) {
            cpf = cpf.replace(/[^\d]+/g, '');
            if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

            let sum = 0;
            let remainder;

            for (let i = 1; i <= 9; i++) {
                sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            }

            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.substring(9, 10))) return false;

            sum = 0;
            for (let i = 1; i <= 10; i++) {
                sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            }

            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.substring(10, 11))) return false;

            return true;
        }

        // Adicionar evento de validação ao campo de CPF
        cpfInput.addEventListener('blur', () => {
            if (!validateCPF(cpfInput.value)) {
                document.getElementById('cpfError').classList.remove('hidden');
            } else {
                document.getElementById('cpfError').classList.add('hidden');
            }
        });
    </script>
</body>

</html>
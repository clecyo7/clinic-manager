<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Paciente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 5px; }
        .header p { margin: 2px; font-size: 14px; color: #555; }
        .divider { border-top: 2px solid #000; margin: 10px 0; }
        .info { margin-top: 20px; font-size: 16px; }
        .info p { margin: 5px 0; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>

    <!-- Cabeçalho da Clínica -->
    <div class="header">
        <h1>{{ $clinica['nome'] }}</h1>
        <p>{{ $clinica['endereco'] }}</p>
        <p>Telefone: {{ $clinica['telefone'] }} | Email: {{ $clinica['email'] }}</p>
    </div>

    <div class="divider"></div>

    <h2 style="text-align: center;">{{ $titulo }}</h2>

    <div class="info">
        <p><strong>Nome:</strong> {{ $paciente['nome'] }}</p>
        <p><strong>Email:</strong> {{ $paciente['email'] }}</p>
        <p><strong>Telefone:</strong> {{ $paciente['telefone'] }}</p>
        <p><strong>Data de Nascimento:</strong> {{ $paciente['data_nascimento'] }}</p>
        <p><strong>Gênero:</strong> {{ $paciente['genero'] }}</p>
        <p><strong>Profissão:</strong> {{ $paciente['profissao'] }}</p>
        <p><strong>Estado Civil:</strong> {{ $paciente['estado_civil'] }}</p>
        <p><strong>Contato de Emergência:</strong> {{ $paciente['contato_emergencia'] }}</p>
        <p><strong>Endereço:</strong> {{ $paciente['endereco'] }}</p>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado em {{ date('d/m/Y') }}</p>
    </div>

</body>
</html>

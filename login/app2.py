# NOVO: Rota para o login
from urllib import request


@app.route('/login', methods=['POST']) # type: ignore
def login():
    data = request.get_json()

    if not data or not 'email' in data or not 'senha' in data:
        return jsonify({'message': 'E-mail e senha são obrigatórios!'}), 400 # type: ignore

    email = data['email']
    senha = data['senha']

    # Busca o usuário no banco de dados pelo e-mail
    usuario = usuario.query.filter_by(email=email).first()

    # Verifica se o usuário existe E se a senha está correta
    # A função check_password faz a mágica de comparar a senha digitada com a senha criptografada
    if not usuario or not usuario.check_password(senha):
        return jsonify({'message': 'E-mail ou senha incorretos'}), 401 # type: ignore # 401 = Não autorizado

    # Se chegou até aqui, o login é válido
    return jsonify({'message': 'Login bem-sucedido!'}), 200 # type: ignore
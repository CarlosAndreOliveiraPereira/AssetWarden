# app.py

from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
import bcrypt

# --- CONFIGURAÇÃO INICIAL ---

app = Flask(__name__)
# Habilita o CORS para permitir que o frontend acesse este backend
CORS(app) 

# Configura o caminho do banco de dados SQLite. Ele será criado na mesma pasta.
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///database.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

# Inicializa a extensão SQLAlchemy
db = SQLAlchemy(app)

# --- MODELO DO BANCO DE DADOS ---

# Define a estrutura da tabela 'usuario' no banco de dados
class Usuario(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(100), nullable=False)
    email = db.Column(db.String(100), unique=True, nullable=False)
    senha_hash = db.Column(db.String(200), nullable=False) # Coluna para a senha criptografada

    def __init__(self, nome, email, senha):
        self.nome = nome
        self.email = email
        # Criptografa a senha antes de salvar
        self.senha_hash = bcrypt.hashpw(senha.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

    def check_password(self, senha):
        # Verifica se a senha fornecida corresponde à senha criptografada
        return bcrypt.checkpw(senha.encode('utf-8'), self.senha_hash.encode('utf-8'))

# --- ROTAS DA API ---

# Rota para o cadastro (endpoint)
@app.route('/cadastrar', methods=['POST'])
def cadastrar():
    # Pega os dados JSON enviados pelo frontend
    data = request.get_json()

    # Validação simples dos dados recebidos
    if not data or not 'nome' in data or not 'email' in data or not 'senha' in data:
        return jsonify({'message': 'Dados incompletos!'}), 400

    nome = data['nome']
    email = data['email']
    senha = data['senha']

    # Verifica se o e-mail já existe no banco de dados
    if Usuario.query.filter_by(email=email).first():
        return jsonify({'message': 'Este e-mail já está em uso!'}), 409 # 409 = Conflito

    # Cria uma nova instância do usuário (a senha é criptografada no __init__)
    novo_usuario = Usuario(nome=nome, email=email, senha=senha)

    # Adiciona o novo usuário à sessão do banco de dados e salva
    try:
        db.session.add(novo_usuario)
        db.session.commit()
        return jsonify({'message': 'Usuário cadastrado com sucesso!'}), 201 # 201 = Criado
    except Exception as e:
        db.session.rollback()
        return jsonify({'message': 'Erro ao salvar no banco de dados!', 'error': str(e)}), 500


# --- EXECUÇÃO ---

if __name__ == '__main__':
    # Cria as tabelas no banco de dados se elas não existirem
    with app.app_context():
        db.create_all()
    # Inicia o servidor Flask
    app.run(debug=True)
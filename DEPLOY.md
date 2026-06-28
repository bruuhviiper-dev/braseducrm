# Deploy no Fly.io

Pré-requisitos: instalar o `flyctl` e ter uma conta no Fly (com cartão cadastrado — não cobra dentro da franquia grátis).

## Instalar o flyctl
- Windows (PowerShell): `iwr https://fly.io/install.ps1 -useb | iex`
- Mac/Linux: `curl -L https://fly.io/install.sh | sh`

## Passos (4 comandos)

```bash
# 1. Login (abre o navegador)
fly auth login

# 2. Cria o app SEM fazer deploy ainda (usa o fly.toml já existente).
#    Quando perguntar, NÃO copie config existente / NÃO crie banco gerenciado.
fly launch --no-deploy --copy-config --name braseducrm --region gru

# 3. Cria o volume persistente do SQLite (1 GB) na mesma região
fly volumes create braseducrm_data --region gru --size 1 --yes

# 4. Gera uma APP_KEY, define como secret e faz o deploy
#    (rode localmente para obter a chave; NUNCA versione a chave no git)
php artisan key:generate --show
fly secrets set APP_KEY="cole-aqui-a-chave-gerada-acima"
fly deploy
```

Ao final, o app fica em `https://braseducrm.fly.dev` (ou o nome que você escolher).

## Credenciais de acesso (criadas pelo seed automático)
- **Login:** `admin`  **Senha:** `admin123`
- Outros: `jessica` / `123456`, `carlos` / `123456`

> Troque a senha do admin antes de entregar ao cliente.

## Observações
- O **banco SQLite** fica no volume `/data` e persiste entre deploys.
- **Arquivos enviados no GED** ficam em `storage/` (não no volume) — resetam em redeploy.
  Se o cliente for subir documentos pra valer, me peça para mover o disco de uploads para o volume.
- Logs: `fly logs`. Console: `fly ssh console`.

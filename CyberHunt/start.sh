#!/bin/bash

# CyberHunt - Security Training Lab
# Start Script

CYAN='\033[0;36m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color
BOLD='\033[1m'

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Banner
echo -e "${CYAN}"
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║                                                           ║"
echo "║   ${BOLD}🎯 CyberHunt - Security Training Lab${NC}${CYAN}                    ║"
echo "║   ${NC}OWASP Top 10 Vulnerable Web Application${CYAN}                ║"
echo "║                                                           ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Check PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}[ERROR]${NC} PHP is not installed. Please install PHP 8.x"
    exit 1
fi

PHP_VERSION=$(php -v | head -n 1 | cut -d ' ' -f 2)
echo -e "${GREEN}[✓]${NC} PHP version: $PHP_VERSION"

# Check/Initialize Database
if [ ! -f "database/cyberhunt.db" ]; then
    echo -e "${YELLOW}[!]${NC} Database not found. Initializing..."
    php database/init.php
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}[✓]${NC} Database initialized successfully"
    else
        echo -e "${RED}[ERROR]${NC} Database initialization failed"
        exit 1
    fi
else
    echo -e "${GREEN}[✓]${NC} Database found"
fi

# Kill existing PHP server on port 8080
if lsof -Pi :8080 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo -e "${YELLOW}[!]${NC} Stopping existing server on port 8080..."
    kill $(lsof -Pi :8080 -sTCP:LISTEN -t) 2>/dev/null
    sleep 1
fi

# Create necessary directories
mkdir -p uploads/profiles exports

# Start server
PORT=${1:-8080}
echo ""
echo -e "${GREEN}[✓]${NC} Starting CyberHunt on port $PORT..."
echo ""
echo -e "${CYAN}═══════════════════════════════════════════════════════════${NC}"
echo ""
echo -e "   ${BOLD}🌐 Access the application at:${NC}"
echo -e "   ${GREEN}http://localhost:$PORT/${NC}"
echo ""
echo -e "   ${BOLD}📖 Vulnerability Documentation:${NC}"
echo -e "   ${YELLOW}../VULNERABILITIES.md${NC}"
echo ""
echo -e "${CYAN}═══════════════════════════════════════════════════════════${NC}"
echo ""
echo -e "${YELLOW}[!]${NC} Press Ctrl+C to stop the server"
echo ""

# Start PHP built-in server with router for proper 404 handling
php -S localhost:$PORT router.php

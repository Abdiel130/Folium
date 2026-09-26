#!/bin/bash
# xdebug-setup.sh
#
# Configura el port-forwarding necesario para que Xdebug funcione
# en entornos WSL2 + Docker Desktop.
#
# PROBLEMA: Docker Desktop corre en una VM de Hyper-V separada del WSL2.
# Los contenedores no tienen ruta directa al WSL2. Se necesita que Windows
# reenvíe el puerto 9003 desde su interfaz de red hacia el WSL2.
#
# SOLUCIÓN: Usar PowerShell desde WSL2 para configurar el port-forward en Windows.
#
# USO:
#   ./xdebug-setup.sh          # Configurar port-forward
#   ./xdebug-setup.sh remove   # Remover port-forward
#
# REQUISITOS: Docker Desktop con WSL2 integration activa

PORT=9003
WSL2_IP=$(ip -4 addr show eth0 | grep -oP '(?<=inet\s)\d+(\.\d+){3}' | head -1)

if [ -z "$WSL2_IP" ]; then
    echo "❌ No se pudo obtener la IP del WSL2"
    exit 1
fi

# Verificar que PowerShell está disponible (WSL2 tiene acceso a comandos de Windows)
if ! command -v powershell.exe &>/dev/null; then
    echo "❌ PowerShell no disponible. ¿Estás en WSL2?"
    exit 1
fi

remove_portproxy() {
    echo "🗑️  Removiendo port-forward de Windows para puerto $PORT..."
    powershell.exe -Command "netsh interface portproxy delete v4tov4 listenport=$PORT listenaddress=0.0.0.0" 2>/dev/null
    echo "✅ Port-forward removido"
}

setup_portproxy() {
    echo ""
    echo "🔧 Configurando Xdebug para WSL2 + Docker Desktop"
    echo "══════════════════════════════════════════════════"
    echo "  IP del WSL2: $WSL2_IP"
    echo "  Puerto:      $PORT"
    echo ""

    # Remover configuración anterior si existe
    powershell.exe -Command "netsh interface portproxy delete v4tov4 listenport=$PORT listenaddress=0.0.0.0" 2>/dev/null

    # Configurar port-forward en Windows: 0.0.0.0:9003 → WSL2:9003
    echo "📡 Configurando port-forward en Windows..."
    RESULT=$(powershell.exe -Command "netsh interface portproxy add v4tov4 listenport=$PORT listenaddress=0.0.0.0 connectport=$PORT connectaddress=$WSL2_IP" 2>&1)

    if [ $? -eq 0 ]; then
        echo "✅ Port-forward configurado exitosamente"
        echo ""
        echo "📋 Configuración activa:"
        powershell.exe -Command "netsh interface portproxy show v4tov4" 2>/dev/null | grep -A1 "Listen\|$PORT"
        echo ""
        echo "📋 Pasos para depurar:"
        echo "  1. ✅ Port-forward activo (Windows → WSL2)"  
        echo "  2. → Presiona F5 en VS Code ('Listen for Xdebug (Docker)')"
        echo "  3. → Pon un breakpoint en tu código PHP"
        echo "  4. → Abre http://localhost:8080 en el navegador"
        echo ""
        echo "ℹ️  Para remover la configuración: ./xdebug-setup.sh remove"
    else
        echo "❌ Error configurando port-forward: $RESULT"
        echo ""
        echo "💡 Alternativa: Ejecuta esto en PowerShell como Administrador:"
        echo "   netsh interface portproxy add v4tov4 listenport=$PORT listenaddress=0.0.0.0 connectport=$PORT connectaddress=$WSL2_IP"
    fi
}

case "${1:-setup}" in
    remove|rm|delete)
        remove_portproxy
        ;;
    setup|*)
        setup_portproxy
        ;;
esac

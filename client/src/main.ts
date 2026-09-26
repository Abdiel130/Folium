interface BackendResponse {
    status: string;
    message: string;
    environment: {
        php_version: string;
        request_uri: string;
        method: string;
        engine: string;
    };
    timestamp: string;
}

const checkBackendBtn = document.querySelector<HTMLButtonElement>('#checkBackend');
const responseContainer = document.querySelector<HTMLDivElement>('#backendResponse');

async function syncWithBackend(): Promise<void> {
    if (!responseContainer || !checkBackendBtn) return;

    checkBackendBtn.textContent = 'Syncing...';
    checkBackendBtn.disabled = true;

    try {
        // Since we are running Vite on 5173 and PHP on 8080 (via Nginx)
        // We'll need to handle CORS or just use the absolute URL for this demo.
        const response = await fetch('http://localhost:8080/api/status');
        const data: BackendResponse = await response.json();

        responseContainer.style.display = 'block';
        responseContainer.innerHTML = `
            <div style="color: var(--primary); margin-bottom: 0.5rem;">> Connection Established</div>
            <pre>${JSON.stringify(data, null, 2)}</pre>
        `;
    } catch (error) {
        responseContainer.style.display = 'block';
        responseContainer.innerHTML = `
            <div style="color: #ef4444; margin-bottom: 0.5rem;">> Connection Failed</div>
            <div style="color: var(--text-dim);">Ensure docker-compose is running and backend is reachable at port 8080.</div>
        `;
        console.error('Backend sync error:', error);
    } finally {
        checkBackendBtn.textContent = 'Sync Backend';
        checkBackendBtn.disabled = false;
    }
}

checkBackendBtn?.addEventListener('click', () => {
    syncWithBackend();
});

console.log('Folium Ecosystem Initialized');

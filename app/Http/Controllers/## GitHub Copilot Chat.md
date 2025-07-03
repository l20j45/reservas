## GitHub Copilot Chat

- Extension Version: 0.28.5 (prod)
- VS Code: vscode/1.101.2
- OS: Windows

## Network

User Settings:
```json
  "github.copilot.advanced.debug.useElectronFetcher": true,
  "github.copilot.advanced.debug.useNodeFetcher": false,
  "github.copilot.advanced.debug.useNodeFetchFetcher": true
```

Connecting to https://api.github.com:
- DNS ipv4 Lookup: 140.82.114.6 (8 ms)
- DNS ipv6 Lookup: Error (3 ms): getaddrinfo ENOTFOUND api.github.com
- Proxy URL: None (0 ms)
- Electron fetch (configured): HTTP 200 (203 ms)
- Node.js https: HTTP 200 (212 ms)
- Node.js fetch: HTTP 200 (215 ms)
- Helix fetch: HTTP 200 (267 ms)

Connecting to https://api.individual.githubcopilot.com/_ping:
- DNS ipv4 Lookup: 140.82.113.21 (3 ms)
- DNS ipv6 Lookup: Error (3 ms): getaddrinfo ENOTFOUND api.individual.githubcopilot.com
- Proxy URL: None (11 ms)
- Electron fetch (configured): HTTP 200 (69 ms)
- Node.js https: HTTP 200 (202 ms)
- Node.js fetch: HTTP 200 (221 ms)
- Helix fetch: HTTP 200 (208 ms)

## Documentation

In corporate networks: [Troubleshooting firewall settings for GitHub Copilot](https://docs.github.com/en/copilot/troubleshooting-github-copilot/troubleshooting-firewall-settings-for-github-copilot).

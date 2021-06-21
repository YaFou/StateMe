import {defineConfig} from "vite";
import {resolve} from "path";

export default defineConfig({
    root: resolve(__dirname, 'assets'),
    build: {
        outDir: '../public/assets',
        rollupOptions: {
            input: {
                app: resolve(__dirname, 'assets/app.ts')
            }
        },
        manifest: true,
        assetsDir: ''
    }
})

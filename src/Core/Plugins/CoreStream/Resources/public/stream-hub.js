console.log('[stream-hub] loaded')

(function () {
    function createApp(root) {
        return {
            root,
            register(plugin) {
                console.log('[stream-hub] register plugin:', plugin.name)

                if (!plugin.init) {
                    return
                }

                plugin.init({
                    app: this,
                    root,
                    query(selector) {
                        return root.querySelector(selector)
                    },
                    queryAll(selector) {
                        return Array.from(root.querySelectorAll(selector))
                    },
                })
            },
        }
    }

    function mount(root) {
        return createApp(root)
    }

    const defaultRoot = document.querySelector('[data-stream-hub-root]') || document.body
    const defaultApp = mount(defaultRoot)

    window.StreamHubApp = {
        mount,
        register(plugin) {
            defaultApp.register(plugin)
        },
    }
})()

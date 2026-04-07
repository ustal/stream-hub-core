console.log('[chat] loaded')

window.ChatApp = {
    register(plugin) {
        console.log('[chat] register plugin:', plugin.name)

        if (plugin.init) {
            plugin.init(this)
        }
    }
}

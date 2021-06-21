const { } = require('../index')

/**
 * 
 * @param {*} commands 
 * @param {*} name 
 * @param {*} isExperimental Only for developpers
 * @param {*} directory
 * @param {*} file 
 * @param {*} Colors 
 */
exports.registerCommand = async(commands, name = "default", isExperimental = false, directory = "commands/", file = "default", Colors) => {
    let props = require('../' + directory + file + ".js")
    commands.set(name, props)

    console.log(Colors.yellow(`Commande enregistré: ${file}.js`))
}
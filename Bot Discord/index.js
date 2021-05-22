const Discord = require('discord.js')

exports.Version = "v1.0"

exports.Client = new Discord.Client()
exports.Client.commands = new Discord.Collection();
exports.Config = require('./datafolder/config.json');
exports.Colors = require('colors');

const CommandRegistry = require('./commands/CommandRegister')
CommandRegistry.registerCommand(exports.Client.commands, "player", true, "commands/minecraft/", "LinkCommand", exports.Colors).then(r => console.log(r))

require('./listener/members/Messages')

console.log(exports.Colors.rainbow("Museum Bot Oppérationel"))
exports.Client.login(exports.Config.token)
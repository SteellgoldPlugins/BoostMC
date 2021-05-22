const Discord = require('discord.js')

exports.Version = "v1.0"

exports.Client = new Discord.Client()
exports.Client.commands = new Discord.Collection();
exports.Config = require('./conf/config.json');
exports.Colors = require('colors');

const sqlite3 = require('sqlite3').verbose();
exports.Database = new sqlite3.Database(':memory:');

const CommandRegistry = require('./commands/CommandRegister')
CommandRegistry.registerCommand(exports.Client.commands, "player", true, "commands/minecraft/", "PlayerCommand", exports.Colors)

require('./listener/members/Messages')

console.log(exports.Colors.rainbow("Museum Bot Oppérationel"))
exports.Client.login(exports.Config.token)
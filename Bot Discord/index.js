const Discord = require('discord.js')

const sqlite3 = require('sqlite3').verbose();

exports.Version = "v1.0"
exports.Prefix = "*"

exports.Client = new Discord.Client({ intents: ['GUILDS', 'GUILD_MESSAGES'] })
exports.Client.commands = new Discord.Collection();
exports.Config = require('./conf/config.json');
exports.Colors = require('colors');
exports.fs = require('fs');
exports.MessageButton = require('discord-buttons')(exports.Client);
const mysql = require('mysql');

exports.Database = mysql.createConnection({
    host     : 'localhost',
    user     : 'boostmc',
    password : 'ecsvhAw2RN#?d9&mVMK0QxQeVJVVQENIvlj4DEgc',
    database : 'faction'
});

const CommandRegistry = require('./commands/CommandRegister')
CommandRegistry.registerCommand(exports.Client.commands, "sendrules", true, "commands/", "SendRules", exports.Colors)
CommandRegistry.registerCommand(exports.Client.commands, "sendroles", true, "commands/", "SendRoles", exports.Colors)
CommandRegistry.registerCommand(exports.Client.commands, "faction", true, "commands/minecraft/", "Faction", exports.Colors)
CommandRegistry.registerCommand(exports.Client.commands, "link", true, "commands/minecraft/", "Link", exports.Colors)

require('./listener/members/Messages')

console.log(exports.Colors.rainbow("Museum Bot Oppérationel"))
exports.Client.login(exports.Config.token)
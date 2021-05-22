const Discord = require('discord.js')

const sqlite3 = require('sqlite3').verbose();

exports.Version = "v1.0"
exports.Prefix = "*"

exports.Client = new Discord.Client()
exports.Client.commands = new Discord.Collection();
exports.Config = require('./conf/config.json');
exports.Colors = require('colors');
exports.fs = require('fs');

exports.Database = new sqlite3.Database('../data.sqlite', (err) => {
    if (err) {
        console.error(err.message);
    }
    console.log('Connected to the chinook database.');
});

const CommandRegistry = require('./commands/CommandRegister')
CommandRegistry.registerCommand(exports.Client.commands, "player", true, "commands/minecraft/", "PlayerCommand", exports.Colors)

require('./listener/members/Messages')

console.log(exports.Colors.rainbow("Museum Bot Oppérationel"))
exports.Client.login(exports.Config.token)
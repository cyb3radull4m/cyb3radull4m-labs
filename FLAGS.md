# Flags — answer key (SPOILERS)

Every lab is **two-flag**, OSWE-style: **FLAG1** proves the access / auth-bypass stage, **FLAG2** proves remote code execution. If you're solving the labs, stop here.

| Lab | FLAG1 — access | FLAG2 — proof (RCE) |
|-----|------|------|
| 01 Looking Glass | `FLAG1{access_the_console_with_a_magic_hash}` | `FLAG2{command_injection_popped_a_shell}` |
| 02 Estate Sale | `FLAG1{forged_remembertoken_impersonation}` | `FLAG2{pop_gadget_wrote_a_webshell}` |
| 03 Render Farm | `FLAG1{client_trusted_role_is_no_role}` | `FLAG2{ejs_ssti_executed_server_side}` |
| 04 Ledger | `FLAG1{blind_sqli_read_the_flag}` | `FLAG2{pickle_deserialization_rce}` |
| 05 Polyfill | `FLAG1{prototype_pollution_made_me_admin}` | `FLAG2{polluted_gadget_ran_my_command}` |

## Verify every lab works

`./verify.sh` builds each lab, runs its reference exploit, and confirms **both** flags:

```
$ ./verify.sh
== 01-looking-glass  (http://127.0.0.1:8081) ==
  PASS  access=FLAG1{...}  proof=FLAG2{...}
...
  passed=5  failed=0  of 5
```

Run one lab: `./verify.sh 03-render-farm`. Exit code is 0 only if every selected lab yields both
flags. Requires docker (with compose), python3, and curl.

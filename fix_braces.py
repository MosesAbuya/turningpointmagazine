import os

directory = r'e:\xampp\htdocs\turningpoint\admin'
import re

for filename in os.listdir(directory):
    if not filename.endswith('.php'): continue
    filepath = os.path.join(directory, filename)
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    # We need to replace:
    #         }
    #     });
    # (where the }); belongs to an outer function)
    # with:
    #         } });
    #     });
    
    # Or instead of parser, we can just replace the exact code snippet for the 10 files!
    
    content = content.replace("            });\n        }\n    });", "            });\n        } });\n    });")
    content = content.replace("            });\n        }\n});", "            });\n        } });\n});")
    content = content.replace("            });\n        }\n    }\n</script>", "            });\n        } });\n    }\n</script>")
    content = content.replace("            });\n        }\n    };\n</script>", "            });\n        } });\n    };\n</script>")
    content = content.replace("            });\n        }\n    } else", "            });\n        } });\n    } else")
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

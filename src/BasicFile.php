<?php
namespace ZxFiles;

class BasicFile
{
    use ByteParser;
    protected static $tokensMap = array(
        0   => '',
        1   => '',
        2   => '',
        3   => '',
        4   => '',
        5   => '',
        6   => '',
        7   => '',
        8   => '',
        9   => '',
        10  => '',
        11  => '',
        12  => '',
        13  => '',
        14  => '',
        15  => '',
        16  => '',
        17  => '',
        18  => '',
        19  => '',
        20  => '',
        21  => '',
        22  => '',
        23  => '',
        24  => '',
        25  => '',
        26  => '',
        27  => '',
        28  => '',
        29  => '',
        30  => '',
        31  => '',
        32  => ' ',
        33  => '!',
        34  => '"',
        35  => '#',
        36  => '$',
        37  => '%',
        38  => '&',
        39  => '\'',
        40  => '(',
        41  => ')',
        42  => '*',
        43  => '+',
        44  => ',',
        45  => '-',
        46  => '.',
        47  => '/',
        48  => '0',
        49  => '1',
        50  => '2',
        51  => '3',
        52  => '4',
        53  => '5',
        54  => '6',
        55  => '7',
        56  => '8',
        57  => '9',
        58  => ':',
        59  => ';',
        60  => '<',
        61  => '=',
        62  => '>',
        63  => '?',
        64  => '@',
        65  => 'A',
        66  => 'B',
        67  => 'C',
        68  => 'D',
        69  => 'E',
        70  => 'F',
        71  => 'G',
        72  => 'H',
        73  => 'I',
        74  => 'J',
        75  => 'K',
        76  => 'L',
        77  => 'M',
        78  => 'N',
        79  => 'O',
        80  => 'P',
        81  => 'Q',
        82  => 'R',
        83  => 'S',
        84  => 'T',
        85  => 'U',
        86  => 'V',
        87  => 'W',
        88  => 'X',
        89  => 'Y',
        90  => 'Z',
        91  => '[',
        92  => '/',
        93  => ']',
        94  => '^',
        95  => '_',
        96  => '`',
        97  => 'a',
        98  => 'b',
        99  => 'c',
        100 => 'd',
        101 => 'e',
        102 => 'f',
        103 => 'g',
        104 => 'h',
        105 => 'i',
        106 => 'j',
        107 => 'k',
        108 => 'l',
        109 => 'm',
        110 => 'n',
        111 => 'o',
        112 => 'p',
        113 => 'q',
        114 => 'r',
        115 => 's',
        116 => 't',
        117 => 'u',
        118 => 'v',
        119 => 'w',
        120 => 'x',
        121 => 'y',
        122 => 'z',
        123 => '{',
        124 => '|',
        125 => '}',
        126 => '~',
        127 => '©',
        128 => '',
        129 => '▝',
        130 => '▘',
        131 => '',
        132 => '▗',
        133 => '',
        134 => '▚',
        135 => '▜',
        136 => '▖',
        137 => '▞',
        138 => '',
        139 => '▛',
        140 => '',
        141 => '▟',
        142 => '▙',
        143 => '',
        144 => '[A]',
        145 => '[B]',
        146 => '[C]',
        147 => '[D]',
        148 => '[E]',
        149 => '[F]',
        150 => '[G]',
        151 => '[H]',
        152 => '[I]',
        153 => '[J]',
        154 => '[K]',
        155 => '[L]',
        156 => '[M]',
        157 => '[N]',
        158 => '[O]',
        159 => '[P]',
        160 => '[Q]',
        161 => '[R]',
        162 => '[S]',
        163 => '[T]',
        164 => '[U]',
        165 => 'RND',
        166 => 'INKEY$',
        167 => 'PI',
        168 => 'FN ',
        169 => 'POINT ',
        170 => 'SCREEN$ ',
        171 => 'ATTR ',
        172 => 'AT ',
        173 => 'TAB ',
        174 => 'VAL$ ',
        175 => 'CODE ',
        176 => 'VAL ',
        177 => 'LEN ',
        178 => 'SIN ',
        179 => 'COS ',
        180 => 'TAN ',
        181 => 'ASN ',
        182 => 'ACS ',
        183 => 'ATN ',
        184 => 'LN ',
        185 => 'EXP ',
        186 => 'INT ',
        187 => 'SQR ',
        188 => 'SGN ',
        189 => 'ABS ',
        190 => 'PEEK ',
        191 => 'IN ',
        192 => 'USR ',
        193 => 'STR$ ',
        194 => 'CHR$ ',
        195 => 'NOT ',
        196 => 'BIN ',
        197 => ' OR ',
        198 => ' AND ',
        199 => '<=',
        200 => '>=',
        201 => '<>',
        202 => ' LINE ',
        203 => ' THEN ',
        204 => ' TO ',
        205 => ' STEP ',
        206 => ' DEF FN ',
        207 => ' CAT ',
        208 => ' FORMAT ',
        209 => ' MOVE ',
        210 => ' ERASE ',
        211 => ' OPEN #',
        212 => ' CLOSE #',
        213 => ' MERGE ',
        214 => ' VERIFY ',
        215 => ' BEEP ',
        216 => ' CIRCLE ',
        217 => ' INK ',
        218 => ' PAPER ',
        219 => ' FLASH ',
        220 => ' BRIGHT ',
        221 => ' INVERSE ',
        222 => ' OVER ',
        223 => ' OUT ',
        224 => ' LPRINT ',
        225 => ' LLIST ',
        226 => ' STOP ',
        227 => ' READ ',
        228 => ' DATA ',
        229 => ' RESTORE ',
        230 => ' NEW ',
        231 => ' BORDER ',
        232 => ' CONTINUE ',
        233 => ' DIM ',
        234 => ' REM ',
        235 => ' FOR ',
        236 => ' GO TO ',
        237 => ' GO SUB ',
        238 => ' INPUT ',
        239 => ' LOAD ',
        240 => ' LIST ',
        241 => ' LET ',
        242 => ' PAUSE ',
        243 => ' NEXT ',
        244 => ' POKE ',
        245 => ' PRINT ',
        246 => ' PLOT  ',
        247 => ' RUN ',
        248 => ' SAVE ',
        249 => ' RANDOMIZE ',
        250 => ' IF ',
        251 => ' CLS ',
        252 => ' DRAW ',
        253 => ' CLEAR ',
        254 => ' RETURN ',
        255 => ' COPY ',
    );
    protected $structure;
    protected $binary;

    /**
     * @param mixed $binary
     */
    public function setBinary($binary)
    {
        $this->binary = $binary;
    }

    public function getAsText()
    {
        $result = false;
        if ($rows = $this->getStructure()) {
            $result = '';
            foreach ($rows as $lineNumber => $text) {
                $result .= '  ' . $lineNumber . $text . "\n";
            }
        }
        return $result;
    }

    public function getAsHtml()
    {
        $result = false;
        if ($rows = $this->getStructure()) {
            $result = '';
            foreach ($rows as $lineNumber => $text) {
                $result .= '&nbsp;&nbsp' . $lineNumber . $text . "<br />";
            }
        }
        return $result;
    }


    public function getStructure()
    {
        if ($this->structure === null) {
            if ($this->binary) {
                $this->structure = [];

                $offset = 0;
                while ($offset < strlen($this->binary)) {
                    $lineNumber = $this->parseWordBigEndian($this->binary, $offset);
                    if ($lineNumber == 32938){
                        //last line
                        break;
                    }
                    $offset++;
                    $offset++;
                    $this->structure[$lineNumber] = '';

                    if ($lineLength = $this->parseWord($this->binary, $offset)) {
                        $offset++;
                        $offset++;

                        while ($lineLength--) {
                            $i = $this->parseByte($this->binary, $offset);
                            $offset++;
                            if ($i == 0x0d) {
                                //end of line
                            } elseif ($i == 0x0e) {
                                //skip number value
                                $offset += 4;
                                $lineLength -= 4;
                            } else {
                                $this->structure[$lineNumber] .= static::$tokensMap[$i];
                            }
                        }
                    }
                }
            }
        }
        return $this->structure;
    }
}
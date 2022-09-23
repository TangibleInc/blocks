/**
 * Evaluate current conditions to determine block control visibility
 *
 * @see gutenberg-template-editor/blocks/dynamic/create.js
 * @see beaver-template-editor/modules/dynamic/module.js
 * @see elementor-template-editor/widgets/dynamic/visibility.js
 */

export default class ControlVisibility {

  constructor(conditionsMap) {

    this.conditionsMap = conditionsMap || []

    this.getPreviousLevels = this.getPreviousLevels.bind(this)
    this.getCondition = this.getCondition.bind(this)
  }

  evaluateConditions(conditions, getFieldValue) {

    let visibility = true

    if( ! conditions || ! Array.isArray(conditions) ) return visibility

    for (const key of conditions) {
      const condition = this.getCondition(key)
      if ( ! this.evaluateCondition(condition, getFieldValue) ) return false
    }

    return visibility
  }

  /**
   * Complete conditions are not stored in items, but it has a key to get it
   *
   * Key structure is: 0-0
   *
   * First is the condition number, refering to an ensemble like:
   * <If>  .... <Else if> .... <If>
   *
   * Second is the condition level, refering to which level of the
   * ensemble needs to be applied (only the starting <If> is 0 for example)
   */
  getCondition(key) {

    const indexes = key.split('-')

    const number = parseInt(indexes[0])
    const level  = parseInt(indexes[1])

    const data = {
      number: number,
      level: level,
      current: this.conditionsMap[ number ][ level ],
      previousLevels: this.getPreviousLevels( number, level )
    }

    return data
  }

  getPreviousLevels(conditionNumber, conditionLevel) {

    // If level is 0, it's the first <If> and there is no previous levels
    if(conditionLevel === 0) return []

    let currentLevel = 0
    const previousConditions = []

    while (currentLevel < conditionLevel) {
      previousConditions.push( this.conditionsMap[ conditionNumber ][ currentLevel ] )
      currentLevel ++
    }

    return previousConditions
  }

  /**
   * Extract standardized comparisons from defined rules
   */
  extractComparisons(rules) {

    const comparisons = []

    for (const key in rules) {

      if ((key==='compare' || key.slice(0, 8)==='compare_')
          || (key==='value' || key.slice(0, 6)==='value_')
      ) continue

      if (key==='control' || key.slice(0, 8)==='control_') {

        const field = rules[key]
        const [_, index = 0] = key.split('_')

        comparisons.push({
          field,
          value:   rules[`value${ index ? '_'+index : '' }`],
          compare: rules[`compare${ index ? '_'+index : '' }`],
        })
        continue
      }

      // Unknown attribute - Assume controlName=expectedValue for backward compatibility

      comparisons.push({
        field: key,
        value: rules[key]
      })
    }

    // console.log('extractComparisons', rules, comparisons)
    return comparisons
  }

  evaluateCondition(condition, getFieldValue) {

    let visibility = true

    /**
     * Current condition only applies when all other conditions from
     * previous levels are false
     *
     * <If> -> <Else if/> -> <Else if/> ... etc
     */
    for (const previousCondition of condition.previousLevels) {

      const comparisons = this.extractComparisons(previousCondition)

      for (const { field, value, compare } of comparisons) {

        const fieldValue = getFieldValue(field)

        // Ignore the rest if any previous level matched
        if ( this.compare(fieldValue, value, compare) ) return false
      }
    }

    // Current condition

    const comparisons = this.extractComparisons(condition.current)

    for (const { field, value, compare } of comparisons) {

      const fieldValue = getFieldValue(field)

      // Ignore the rest if any comparison does not match
      if ( !this.compare(fieldValue, value, compare) ) return false
    }

    return visibility
  }

  /**
   * Shared comparison function to apply supported operators
   */
  compare(firstValue, secondValue, operator = 'is') {

    let result = false

    switch (operator) {
    case 'is':
    case 'equal':
    case 'not':
    default:
      result = firstValue === secondValue
      if (operator==='not') result = !result
      break
    }

    return result
  }
}
